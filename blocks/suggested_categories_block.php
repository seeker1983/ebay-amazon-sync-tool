<?
require_once('lib/ebay/item.php');


$response = Ebay::get_suggested_categories($item['title']);

if($response->CategoryCount == 0)
{
	$response = Ebay::get_suggested_categories(htmlentities($item['desc']));
}



foreach ($response->SuggestedCategoryArray->SuggestedCategory as $cat) 
{
	$category_parents = array();
	foreach($cat->Category->CategoryParentName as $val)
		$category_parents[] = $val;

	$categories[$cat->Category->CategoryID] = implode(' => ', $category_parents);
	
}

?>

<div id="categories_tree">
	<div class="control-group">
		<label class="control-label" for="searchField">Category</label>&nbsp;&nbsp;&nbsp;&nbsp;
			<select class="category" id="category_name" onchange="$('#category_id').val($(this).val())" style='width:800px'>
			  	<option value=""> SELECT CATEGORY </option>
			  <? foreach($categories as $id=>$name) { ?>
			  	<option value="<? echo $id; ?>"> <? echo htmlentities($name); ?> </option>
			  <? } ?>
			</select>
	</div>
</div>

<div class="control-group">
  <label class="control-label" for="searchField">CategoryId</label>
  <div class="controls">
    <input id="category_id" name="category_id" placeholder="CategoryId" class="input-large saveable" type="text" onchange="change(this)">
  </div>
</div>

<script>
	$(document).ready(function(){
	})

	function category_changed(el)
	{

	}

</script>


