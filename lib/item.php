<?php
define('EBAY_CHARGE', 0.15);
require_once("lib/ebay/item.php");

class Item
{
	public $local_data = null;
	public $item_id = null;
	public $sku = null;
	public $vendor_data = null;
    public $ebay_data = null;

    public function __construct($item_id = null)
    {
        if($item_id)
        {
            $this->item_id = (string)$item_id;
            $this->local_data = DB::query_row("SELECT * from `user_products` where ItemID='$item_id'");
            if(!empty($this->local_data['SKU']))
                $this->sku = $this->local_data['SKU'];
        }
    }

    public function load_local_data()
    {
       if($this->item_id)
            $this->local_data = DB::query_row("SELECT * from `user_products` where ItemID='$this->item_id'");

       return $this->local_data;
    }

    public static function from_ebay_data($ebay_data)
    {
        $item = new Item((string) $ebay_data->ItemID);

        $item->ebay_data = $ebay_data;
        $item->sku = $ebay_data->SKU;

        return $item;
    }

    public function load_ebay_data()
    {
        if(!$this->ebay_data)
        {
            $response =  Ebay::get_item($this->item_id);

            if($response->Ack =='Success')
            {
                $this->ebay_data = $response->Item;  
                $this->sku = $this->ebay_data->SKU;
            }
            else
            {                
                $this->log_response_errors($response);
            }
        }
    }
    public function log_response_errors($response)
    {
        ob_start();

        show_response_errors($response);

        $this->log(ob_get_clean());
    }




	public function exists()
	{
		return (bool) $this->local_data;
	}

	public function scrape($sku = null)
	{
		if(!$this->vendor_data)
		{			
			require_once("lib/scrape.php");

			if(is_null($sku))
				$sku = $this->sku;

			$this->vendor_data = scrap_item($sku);

            if($this->item_id)
            {
                if(! $this->load_local_data() )
                {
                    $this->load_ebay_data();
                    $this->create_local();
                }
            }

            if(LOCAL_SERVER)
            {
//                if(preg_match('%^B%', $sku))
//                    $this->vendor_data['offerprice'] /= 25;
            }

		}
//        if(!isset($this->vendor_data['prime']))
//            xp($this->vendor_data);

        if($this->vendor_data['prime'] != 'Yes')
            $this->vendor_data['quantity'] = 0;
		return $this->vendor_data;
	}

	public function rec_price()
	{
		if($this->vendor_data['offerprice'])
		{
			return ( $this->vendor_data['offerprice'] * (1 + $this->get_profit_ratio()) ) / (1 - EBAY_CHARGE);
		}
		return null;
	}
	
	public function get_profit_ratio()
	{
		if($this->local_data)
			return $this->local_data['ProfitRatio'] / 100;
		
        if($this->vendor_data && preg_match('%^WF%', $this->sku))
			return 0;

		return 0.15;
	}

    public function get_links()
    {
        return "<a href='" . $this->local_data['ItemUrl']  . "'>".$this->local_data['Title']."</a>" . "(<a href='" . $this->local_data['VendorUrl']  . "'>".$this->local_data['SKU']."</a>) ";
    }
	
	public function log($msg)
	{
        $line = $this->get_links() . $msg;
		Log::custom("item_$this->item_id.txt", $msg);
		Log::push($line);
	}

	public function update()
	{
        global $_user;

		$this->scrape();

        if($this->vendor_data['scrapok'])
        {
            if($this->vendor_data['prime'] != 'Yes')
            {
                if($this->exists())
                {
                    $this->log("Out of stock");
    //                $_user->notify("Item is out of stock", $this->get_links() . ' is out of stock');

                    /* End of Deletion code*/
                }

            }
            else
            {            
                $this->load_ebay_data();

                if(! $this->exists())
                    $this->create_local();

                $this->revise(array(
                    'vendor-price' => $this->vendor_data['offerprice'],
                    'vendor_quantity' => $this->vendor_data['quantity'],
                    'ebay_quantity' => (string) $this->ebay_data->QuantityAvailable,
                    'max_quantity' => $this->local_data['max_quantity'],
                    'profit-pc' => $this->get_profit_ratio(),
                    'price' => $this->rec_price()
                    ));
            }
                
        }
        else
        {
            $this->log("Scrape failed.");
        }
    }

    function update_local()
    {
        $sql = "UPDATE `user_products` SET `VendorPrice` = '${this}->{vendor_data['offerprice']}',
            `VendorQty` = '${this}->{vendor_data['quantity']}',
            `Qty` = '$this->ebay_data->QuantityAvailable'
            WHERE `user_products`.`ItemID` ='$this->ebay_data->ItemID'";

        $this->query($sql);

    }
    
    function update_ebay()
    {
    	require_once("lib/ebay/item.php");

        $this->log("Updated: price: $price, quantity: $quantity");

    	Ebay::revise_item($item_id, array(
            'quantity' => $this->local_data['max_quantity'],
            'price' => $new_price)
        );
    }
    
    function create_local()
    {
        global $_user;

        $sql = "INSERT INTO  `user_products` (
            `UserID` ,
            `ItemID` ,
            `Qty` ,
            `Price` ,
            `Title` ,
            `SKU` ,
            `Image` ,
            `ItemUrl` ,
            `VendorPrice` ,
            `VendorQty`,
            `ProfitRatio`,
            `VendorUrl`,
            `sort`
            )
            VALUES (
            '$_user->id',  
            '" .  $this->ebay_data->ItemID . "',
            '" .  $this->ebay_data->QuantityAvailable . "', 
            '" .  $this->ebay_data->SellingStatus->CurrentPrice->value . "',
            '" . mysql_real_escape_string($this->ebay_data->Title) . "',  
            '" . mysql_real_escape_string($this->ebay_data->SKU) . "',  
            '" .  $this->ebay_data->PictureDetails->GalleryURL . "',
            '" .  $this->ebay_data->ListingDetails->ViewItemURL . "', 
             '" . $this->vendor_data['offerprice'] . "',  
             '" . $this->vendor_data['quantity'] . "',
             '" . $this->get_profit_ratio()*100 . "',
             '" . $this->vendor_data['url'] . "',
             ''
        )";
       $this->query($sql);

       $this->load_local_data();
    }

    private function query($sql)
    {
        Log::custom("item_$this->item_id.sql.txt", $sql);

        DB::query($sql);

    }

    public function drop()
    {
        $this->log("Item dropped.");

        $response = Ebay::drop_item($this->item_id);

        if($response->Ack !="Success")
        {
            $this->log_response_errors($response);
        }

        return $response;
    }

    public function relist()
    {
        $this->log(" Item is back in stock.");

        $response = Ebay::relist_item($this->item_id);

        if($response->Ack !="Success")
        {
            $this->log_response_errors($response);
        }

        return $response; 
    }

    public function revise($options)
    {

        $sql = "UPDATE `user_products` SET `VendorPrice` = '${options['vendor-price']}',
            `VendorQty` = '${options['vendor_quantity']}',
            `ProfitRatio` = '" . ($options['profit-pc'] * 100) ."',
            `Qty` = '${options['ebay_quantity']}',
            `price` = '${options['price']}',
            `max_quantity` = '${options['max_quantity']}'
       WHERE `user_products`.`ItemID` ='$this->item_id'";

       $this->query($sql);

       if($options['price'] != $this->local_data['Price'] || 
            $options['max_quantity']  != $options['ebay_quantity'] )
        {
            $response = Ebay::revise_item($this->item_id, array(
                'quantity' => $options['max_quantity'],
                'price' => $options['price']
            ));

            $this->log("Updated: price: ${options['price']}, quantity: ${options['max_quantity']}");
        }

        $this->local_data = DB::query_row("SELECT * from `user_products` where ItemID='$this->item_id'");
    }

    public function tail($lines = 0)
    {
        return Log::tail_custom("item_$this->item_id.txt", $lines);
    }

    public function setSort($sort)
    {
        $this->query("UPDATE `user_products` SET `sort`='$sort' WHERE `user_products`.`ItemID` ='$this->item_id'");
    }

    public function get_ebay_data_by_sku($sku)
    {
        return Ebay::get_item_by_sku($sku);
    }

}

 
