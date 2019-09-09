@extends('layouts.apps')

@section('content')



    <div class="container">


        <h4>Currency exchange calculator</h4>
        <h6>{{$title}}</h6>
        <h6>{{$description}}</h6>
<h6>Visit <a href="http://www.floatrates.com/daily/gbp.xml" target="_blank">FloatRate</a>.</h6>


<form>


  <div class="form-row">
<!--Currency From Input box-->
 <div class="col-md-4 mb-3">
    <label for="thecurrencyfrom"><strong>Currency From</strong></label>
    <select multiple class="form-control" id="currencyFromButton">
      <?php foreach ($comboBoxValues as $key => $value){ ?>
      <option onlick="setCurrencyFrom('<?=$key?>')"  value="{{$key}}"><?=$value?></option>
      <?php } ?>
    </select>
  </div>


<!--Currency To Input box-->
 <div class="col-md-4 mb-3">
    <label for="thecurrencyto"><strong>Currency To</strong></label>
    <select multiple class="form-control" id="currencyToButton">
      <?php foreach ($comboBoxValues as $key => $value){ ?>
      <option onlick="setCurrencyTo('<?=$key?>')" value="{{$key}}"><?=$value?></option>
      <?php } ?>
    </select>
  </div>


  <div class="col-md-4 mb-3">
      <label for="amount"><strong>Amount</strong></label>
        <input type="text" name="amount" id="amount" class="form-control" required="">

          <small class="text-danger" style="display:none; text-align:center;" id="nonsuccessMsg"></small>
  </div>
 

   </div>

            <div class="form-group">
                <button class="btn btn-success btn-submit">Submit</button>
                  
            </div>

 
        </form>


 <div class="alert alert-success" style="display:none; text-align:center;" id="successMsg">
            <strong>Conversion Successful!</strong>
  </div>


  <div class="row justify-content-center">
    <div class="col-md">
      <div class="card card-default">
        <div class="card-header">
         <strong> Result </strong>
         
        </div>
        <div class="card-body">
          <ul class="list-group">
               <h4 style="text-align:center;" id="result"></h4>
          </ul>


    </div>




<script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   

    $(".btn-submit").click(function(e){

  

        e.preventDefault();

   

        var currencyfrom = $('#currencyFromButton').val();
        if(!currencyfrom){
          alert('Enter a currency you wish to exchange from');
          return false;
        }
        currencyfrom = currencyfrom[0];

        var currencyto = $('#currencyToButton').val();
        if(!currencyto){
          alert('Enter a currency you wish to exchange to');
          return false;
        }
        currencyto = currencyto[0];
        var amount = $("input[name=amount]").val();
        
       

   
        $.ajax({

           type:'POST',
           url:'/currency',
           data:{currencyfrom:currencyfrom, currencyto:currencyto, amount:amount},

           success:function(data){
            document.getElementById("successMsg").style.display="block";

            //The Result
              $('#result').html(data); 


              /*Error Messages concerning amount value */
            var errorMessage = $('#nonsuccessMsg');
            errorMessage.html(jsonValue.errors.amount[0]).css('display', 'none');

           },
           error:function(xhr){
            document.getElementById("successMsg").style.display="none";
            
//alert(JSON.stringify(xhr.responseJSON.errors));

            /*Error messages concerning amount */
            var errorMessage = $('#nonsuccessMsg');
            
            jsonValue = jQuery.parseJSON( xhr.responseText );
          
            errorMessage.html(jsonValue.errors.amount[0]).css('display', 'block');

         

           }

        });

  

	});

</script>

   

</html>

@endsection