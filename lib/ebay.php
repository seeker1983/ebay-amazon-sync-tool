<?php

$deps_folder = pathinfo(__FILE__,PATHINFO_DIRNAME) . '/' . pathinfo(__FILE__,PATHINFO_FILENAME);

foreach(glob($deps_folder . '/*.php') as $lib)
    require_once($lib);

class Ebay
{
    public static function get_active_items()
    {	
    	$data = EbayApi::get_my_ebay_selling_ActiveList(1, 100);

        $xml = simplexml_load_string($data);

        $total_pages = $xml->ActiveList->PaginationResult->TotalNumberOfPages;
        foreach ($xml->ActiveList->ItemArray->Item as $item) 
            $items[] = $item;  

        for($page =2; $page <=$total_pages; $page++)
        {
            $data = EbayApi::get_my_ebay_selling_ActiveList($page, 100);

            $xml = simplexml_load_string($data);
            
            foreach ($xml->ActiveList->ItemArray->Item as $item) 
                $items[] = $item;            

        }

    	return $items;	    	
    }   
    public static function get_item($id)
    {   
        $result = simplexml_load_string(EbayApi::get_item($id));
        if($result->Ack == 'Success' )
            return $result->Item;
        return false;
    }
}

class EbayTest
{
    public static function dump_items()
    {   
        ?> 
            <table width=100%> 
                <tr>
                    <td><b>ItemId</b></td>
                    <td><b>Url</b></td>
                    <td><b>Price</b></td>
                    <td><b>Quantity</b></td>
                    <td><b></b></td>
                    <td><b></b></td>
                    <td><b></b></td>
                </tr>
        <?
        foreach(Ebay::get_active_items() as $item)
        {
            xd($item);

        }
        ?> </table<?
    }
}

