<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;



  
class HomeController extends Controller

{

    //XML contents converted to array to get currency conversion data
    private function getCurrencyData(){
        $xml_string = file_get_contents("http://www.floatrates.com/daily/gbp.xml");
        
        $xml = new \simpleXMLElement($xml_string);
        $array = (array)$xml;
        $array = json_decode(json_encode($array));

        $comboBoxValues = [];
        $items = (array)$array->item;
        return $items;
    }


    /**
    * This method handles POST requests from the input; currency convert calculation
    */
    public function calculateCurrency(Request $request){


//validation
$this->validate($request, [
    'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:2|',
    'currencyfrom' => 'required',
    'currencyto' => 'required'
]);





        $from = $request->input('currencyfrom');
        $to = $request->input('currencyto');
        $amount = $request->input('amount');


        $dataSource = $this->getCurrencyData();
        
        $currencyList = array_column($dataSource, 'targetCurrency');
        
        $currencyFromItemIndex = array_search($from, $currencyList);
        $currencyFromItem = $dataSource[$currencyFromItemIndex];
        $currencyToItemIndex = array_search($to, $currencyList);
        $currencyToItem = $dataSource[$currencyToItemIndex];


    /*Calculation to convert from currency A to B via pound value
    */

            $pounds = $currencyFromItem->inverseRate * $amount; 
            $returnedConversion = $pounds * $currencyToItem->exchangeRate;


            $roundedConversion = round($returnedConversion, 2);

            $return = $amount. ' ' . $from. ' = ' .$roundedConversion. ' ' .$to;
        



            return $return;



    }
    /**
     * Create a new controller instance.

     *

     * @return void

     */

    public function ajaxRequest()

    {


        $xml_string = file_get_contents("http://www.floatrates.com/daily/gbp.xml");
      
        $xml = new \simpleXMLElement($xml_string);


        $array = (array)$xml;
        $array = json_decode(json_encode($array));

        $comboBoxValues = [];
        $items = (array)$array->item;


        for ($i = 0; $i < sizeof($items); $i++) {
        $item = (array)$items[$i];
        $comboBoxValues[$item['targetCurrency']] = $item['targetName'];
        
        }



        $title =  (string)$xml->title;
        $description = (string)$xml->description;


        return view('ajaxRequest', compact('title','description', 'comboBoxValues'));

    }

   

}