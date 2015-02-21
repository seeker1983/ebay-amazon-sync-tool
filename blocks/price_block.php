<div class="control-group">
  <label class="control-label" for="searchField">Profit </label>
  <div class="controls">
      <input name="vendor-price" type="hidden" value="<?php echo $vendor_price; ?>">
      <input id="vendor-price" disabled placeholder="Vendor price" style='width:120px !important' type="text"
       value="<?php echo $vendor_price; ?>"> * 
      <input id="profit-pc" name="profit-pc"  placeholder="Profit %" style='width:120px !important' type="text" 
      value="<?php echo $profit_pc; ?>"> = 
      <input id="profit-raw" name="profit-raw" placeholder="Profit $" style='width:120px !important' type="text">
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="searchField">Price</label>
  <div class="controls">
    <input id="price" name="price" placeholder="" class="input-large" type="text">                    
  </div>
</div>
               
<script>
$(function(){
  $('#vendor-price').change(function(){
    $('#profit-raw').val(parseFloat($('#vendor-price').val()) * parseFloat($('#profit-pc').val()))
    $('#price').val(((parseFloat($('#vendor-price').val()) + parseFloat($('#profit-raw').val()))/0.85).toFixed(2))
  })
  $('#profit-raw').change(function(){
    $('#profit-pc').val(((parseFloat($('#profit-raw').val())) / parseFloat($('#vendor-price').val())).toFixed(2))
    $('#price').val(((parseFloat($('#vendor-price').val()) + parseFloat($('#profit-raw').val()))/0.85).toFixed(2))
  })

  $('#profit-pc').change(function(){
    $('#profit-raw').val(parseFloat($('#vendor-price').val()) * parseFloat($('#profit-pc').val()))
    $('#price').val(((parseFloat($('#vendor-price').val()) + parseFloat($('#profit-raw').val()))/0.85).toFixed(2))
  })

  $('#vendor-price').change();
})
</script>       
