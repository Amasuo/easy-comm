<?php

namespace App\Helpers;
use App\Models\ProductVariant;
use App\Models\Store;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Coduo\PHPHumanizer\NumberHumanizer;

class DashboardHelper
{
    public static function getNumberOfCustomersForStoreId($storeId)
    {
        $res = DB::select("
        SELECT store_id, COUNT(DISTINCT customer_id) as count
        FROM orders
        WHERE store_id = '$storeId'
        GROUP BY store_id;
        ");

        return $res[0]->count;
    }

    public static function getNumberOfProductVariantsForStoreId($storeId)
    {
        $store = Store::findOrFail($storeId);
        $storesIds = [$store->id]; 
        $childrenIds = $store->children()->pluck('id')->toArray();
        $storesIds = array_merge($storesIds, $childrenIds);

        $productVariantsCount = ProductVariant::whereHas('product', function ($query) use ($storesIds) {
            return $query->whereIn('store_id', $storesIds);
        })->count();

        return $productVariantsCount;
    }

    public static function getNumberOfOrdersForStoreId($storeId)
    {
        $res = DB::select("
        SELECT store_id, COUNT(*) as count
        FROM orders
        WHERE store_id = '$storeId'
        GROUP BY store_id;
        ");

        return $res[0]->count;
    }

    public static function getMonthlyGrowthForStoreId($storeId)
    {
        $data = DB::select("
        WITH MonthlySales AS (
            SELECT 
                result.StoreID AS storeID,
                YEAR(result.OrderDate) AS year,
                MONTH(result.OrderDate) AS month,
                SUM(result.Final_Total_Price) AS TotalSales
            FROM (
                SELECT  
                    MAX(opv.order_id) AS Order_ID,
                    MAX(opv.created_at) AS OrderDate,
                    MAX(opv.count) AS NbrItems,  
                    GROUP_CONCAT(pov.value) AS ProductDetails, 
                    MAX(pr.name) AS Product, 
                    SUM(pr.purchase_price_int) AS Total_purchased_price, 
                    MAX(str.name) AS Store,
                    MAX(str.id) AS StoreID, 
                    COALESCE(SUM(pv.custom_price_int), SUM(pr.price_int)) AS Final_Total_Price
                FROM 
                    order_product_variant opv
                JOIN 
                    product_variants pv ON opv.product_variant_id = pv.id
                JOIN 
                    product_option_value_product_variant pvpov ON pv.id = pvpov.product_variant_id
                JOIN 
                    product_option_values pov ON pvpov.product_option_value_id = pov.id
                JOIN 
                    product_options po ON po.id = pov.product_option_id
                JOIN 
                    products pr ON pr.id = po.product_id
                JOIN 
                    stores str ON str.id = pr.store_id
                GROUP BY 
                    opv.id
            ) AS result
            WHERE 
                result.StoreID = '$storeId'
            GROUP BY 
                result.StoreID, YEAR(result.OrderDate), MONTH(result.OrderDate)
        )
        SELECT
            currentMonth.year,
            currentMonth.month,
            currentMonth.totalSales AS current_month_sales,
            previousMonth.totalSales AS previous_month_sales,
            IFNULL(
                ((currentMonth.totalSales - previousMonth.totalSales) / previousMonth.totalSales) * 100,
                100
            ) AS growth_rate
        FROM
            MonthlySales currentMonth
        LEFT JOIN
            MonthlySales previousMonth
        ON
            currentMonth.storeID = previousMonth.storeID 
            AND (
                (currentMonth.year = previousMonth.year AND currentMonth.month = previousMonth.month + 1)
                OR (currentMonth.year = previousMonth.year + 1 AND currentMonth.month = 1 AND previousMonth.month = 12)
            )
        WHERE
            currentMonth.storeID = '$storeId'
        ORDER BY
            currentMonth.year, currentMonth.month;
        ");

        $colors = [
            'success',
            'error',
            'warning',
            'secondary',
            'error',
        ];
        
        //last 5 months
        $data = array_reverse($data);
        $res = [];
        $i = 0;
        while ($i < 5) {
            $temp = new \stdClass();
            $temp->abbr = Carbon::createFromFormat('!m', $data[$i]->month)->format('M');
            $temp->month = Carbon::createFromFormat('!m', $data[$i]->month)->format('F');
            $temp->amount = NumberHumanizer::metricSuffix($data[$i]->current_month_sales / 10);
            $growthRate = substr($data[$i]->growth_rate, 0, -2);
            $temp->change = ($growthRate[0] == '-' ? $growthRate : '+' . $growthRate) . '%';
            $temp->color = $colors[$i];
            $res[] = $temp;
            $i++;
        }

        return $res;
    }

    public static function getTopProductsForStoreId($storeId)
    {
        $data = DB::select("
        WITH ProductFrequency AS (
            SELECT
                ord.store_id,
                opv.product_variant_id,
                SUM(opv.count) AS totalItems
            FROM
                orders ord
            JOIN
                order_product_variant opv ON ord.id = opv.order_id
            WHERE
                ord.store_id = '$storeId'
            GROUP BY
                ord.store_id,
                opv.product_variant_id
        ),
        RankedProducts AS (
            SELECT
                product_variant_id,
                totalItems,
                ROW_NUMBER() OVER (ORDER BY totalItems DESC) AS prodRank
            FROM
                ProductFrequency
        )
        SELECT
            rp.product_variant_id,
            pr.name,
            rp.totalItems AS items_sold,
            COALESCE(pv.custom_price_int, pr.price_int) AS unit_price,
            rp.totalItems * COALESCE(pv.custom_price_int, pr.price_int) AS total_sales
        FROM
            RankedProducts rp
        JOIN
            product_variants pv ON rp.product_variant_id = pv.id
        JOIN
            products pr ON pr.id = pv.product_id
        WHERE
            prodRank <= 4
        UNION ALL
        
        SELECT
            NULL,
            NULL,
            SUM(rp.totalItems) AS totalItems,
            NULL,
            SUM(rp.totalItems * COALESCE(pv.custom_price_int, pr.price_int)) AS TotalSales
        FROM
            RankedProducts rp
        JOIN
            product_variants pv ON rp.product_variant_id = pv.id
        JOIN
            products pr ON pr.id = pv.product_id
        WHERE
            rp.prodRank <= 4;
        ");

        $res = [];
        foreach($data as $key => $value) {
            if ($value->product_variant_id) {
                $temp = new \stdClass();
                $temp->product_variant = ProductVariant::findOrFail($value->product_variant_id);
                $temp->total_sales = NumberHumanizer::metricSuffix($value->total_sales / 10);
                $temp->items_sold = $value->items_sold;
                $res[] = $temp;
            }
        }

        return $res;
    }

    private static function getWeeklySalesCostsProfitForStoreId($storeId)
    {
        $res = DB::select("
        WITH WeeklySales AS (
            SELECT
                result.StoreID AS StoreId,
                YEAR(result.OrderDate) AS year,
                WEEK(result.OrderDate, 1) AS week,
                SUM(result.Final_Total_Price) AS totalSales,
                SUM(result.Total_purchased_price) AS totalCosts
            FROM
                (
                SELECT  
                    MAX(opv.order_id) AS Order_ID,
                    MAX(opv.created_at) AS OrderDate,
                    MAX(opv.count) AS NbrItems,  
                    GROUP_CONCAT(pov.value) AS ProductDetails, 
                    MAX(pr.name) AS Product, 
                    SUM(pr.purchase_price_int) AS Total_purchased_price, 
                    MAX(str.name) AS Store,
                    MAX(str.id) AS StoreID, 
                    COALESCE(SUM(pv.custom_price_int), SUM(pr.price_int)) AS Final_Total_Price
                FROM 
                    order_product_variant opv
                JOIN 
                    product_variants pv ON opv.product_variant_id = pv.id
                JOIN 
                    product_option_value_product_variant pvpov ON pv.id = pvpov.product_variant_id
                JOIN 
                    product_option_values pov ON pvpov.product_option_value_id = pov.id
                JOIN 
                    product_options po ON po.id = pov.product_option_id
                JOIN 
                    products pr ON pr.id = po.product_id
                JOIN 
                    stores str ON str.id = pr.store_id
                GROUP BY 
                    opv.id) AS result
            WHERE
                storeID = '$storeId'
            GROUP BY
                storeID, YEAR(result.OrderDate), WEEK(result.OrderDate, 1)
        ),
        RecentWeeks AS (
            SELECT DISTINCT
                year,
                week
            FROM
                WeeklySales
            ORDER BY
                year DESC,
                week DESC
            LIMIT 6
        )
        SELECT
            WeeklySales.year,
            WeeklySales.week,
            WeeklySales.totalSales As weekly_sales,
            WeeklySales.totalCosts AS weekly_costs,
            WeeklySales.totalSales - WeeklySales.totalCosts AS weekly_profit
        FROM
            WeeklySales
        JOIN
            RecentWeeks
        ON
            WeeklySales.year = RecentWeeks.year AND
            WeeklySales.week = RecentWeeks.week
        WHERE
            WeeklySales.storeID = '$storeId'
        ORDER BY
            WeeklySales.year DESC,
            WeeklySales.week DESC;
        
        ");

        return $res;
    }

    public static function getWeeklSalesForStoreId($storeId)
    {
        $weeklyValues = self::getWeeklySalesCostsProfitForStoreId($storeId);

        $res = [];
        foreach($weeklyValues as $weeklyValue) {
            $firstDate = new \DateTime();
            $lastDate = new \DateTime();
            $firstDate->setISODate($weeklyValue->year, $weeklyValue->week, 1);
            $lastDate->setISODate($weeklyValue->year, $weeklyValue->week, 7);
            $temp = new \stdClass();
            $temp->year = $weeklyValue->year;
            $temp->week = $firstDate->format('d.m') . '-' . $lastDate->format('d.m');
            $temp->value = $weeklyValue->weekly_sales / 10;
            $res[] = $temp;
        }

        return array_reverse($res);
    }

    public static function getWeeklyCostsForStoreId($storeId)
    {
        $weeklyValues = self::getWeeklySalesCostsProfitForStoreId($storeId);

        $res = [];
        foreach($weeklyValues as $weeklyValue) {
            $firstDate = new \DateTime();
            $lastDate = new \DateTime();
            $firstDate->setISODate($weeklyValue->year, $weeklyValue->week, 1);
            $lastDate->setISODate($weeklyValue->year, $weeklyValue->week, 7);
            $temp = new \stdClass();
            $temp->year = $weeklyValue->year;
            $temp->week = $firstDate->format('d.m') . '-' . $lastDate->format('d.m');
            $temp->value = $weeklyValue->weekly_costs / 10;
            $res[] = $temp;
        }

        return array_reverse($res);
    }

    public static function getWeeklyProfitForStoreId($storeId)
    {
        $weeklyValues = self::getWeeklySalesCostsProfitForStoreId($storeId);

        $res = [];
        foreach($weeklyValues as $weeklyValue) {
            $firstDate = new \DateTime();
            $lastDate = new \DateTime();
            $firstDate->setISODate($weeklyValue->year, $weeklyValue->week, 1);
            $lastDate->setISODate($weeklyValue->year, $weeklyValue->week, 7);
            $temp = new \stdClass();
            $temp->year = $weeklyValue->year;
            $temp->week = $firstDate->format('d.m') . '-' . $lastDate->format('d.m');
            $temp->value = $weeklyValue->weekly_profit / 10;
            $res[] = $temp;
        }

        return array_reverse($res);
    }

    private static function getTotalSalesCostsProfitForStoreId($storeId)
    {
        $res = DB::select("
        SELECT
	        result.Store AS Store,
            SUM(result.Final_Total_Price) As total_sales,
            SUM(result.Total_purchased_price) As total_costs,
            SUM(result.Final_Total_Price) - SUM(result.Total_purchased_price) AS total_profit
        FROM (
	        SELECT 
            -- opv.id, 
            MAX(opv.order_id) AS Order_ID, 
            MAX(opv.count) AS NbrItems, 
            GROUP_CONCAT(pov.value) AS ProductDetails, 
            MAX(pr.name) AS Product, 
            SUM(pr.purchase_price_int) AS Total_purchased_price, 
            MAX(str.name) AS Store,
            COALESCE(SUM(pv.custom_price_int), SUM(pr.price_int)) AS Final_Total_Price
        FROM 
            order_product_variant opv
        JOIN 
            product_variants pv ON opv.product_variant_id = pv.id
        JOIN 
            product_option_value_product_variant pvpov ON pv.id = pvpov.product_variant_id
        JOIN 
            product_option_values pov ON pvpov.product_option_value_id = pov.id
        JOIN 
            product_options po ON po.id = pov.product_option_id
        JOIN 
            products pr ON pr.id = po.product_id
        JOIN 
            stores str ON str.id = pr.store_id
        WHERE str.id = '$storeId'
        GROUP BY 
            opv.id
        ) AS result
        GROUP BY Store;
        ");

        return $res[0];
    }

    public static function getTotalSalesForStoreId($storeId) {
        $temp = self::getTotalSalesCostsProfitForStoreId($storeId);
        return NumberHumanizer::metricSuffix($temp->total_sales / 10);
    }

    public static function getTotalCostsForStoreId($storeId) {
        $temp = self::getTotalSalesCostsProfitForStoreId($storeId);
        return NumberHumanizer::metricSuffix($temp->total_costs / 10);
    }

    public static function getTotalProfitForStoreId($storeId) {
        $temp = self::getTotalSalesCostsProfitForStoreId($storeId);
        return NumberHumanizer::metricSuffix($temp->total_profit / 10);
    }

    public static function getDailyOrdersForStoreId($storeId)
    {
        $startDate = Carbon::today();
        $startDate = $startDate->subDays(value: 120)->format('Y-m-d');

        $data = DB::select("
        SELECT
            DATE(created_at) AS order_date,
            COUNT(*) AS num_orders
        FROM
            orders
        WHERE
            store_id = '$storeId'
            AND created_at >= '$startDate'
        GROUP BY
            order_date
        ORDER BY
            order_date;
        ");

        $res = [];
        foreach ($data as $item) {
            $temp = new \stdClass();
            $temp->order_date = Carbon::createFromFormat('Y-m-d', $item->order_date)->format('d.m');
            $temp->num_orders = $item->num_orders;
            $res[] = $temp;
        }

        return $res;
    }
}
