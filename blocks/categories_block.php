<?
require_once('lib/ebay/item.php');

function get_categories($options)
{
	$cache_id = "categories/" .md5(serialize($options));

    return Cache::get($cache_id, function($options){
		$response = Ebay::get_categories($options);
		
		if($response->Ack == 'Success')
		{
			foreach ($response->CategoryArray->Category as $category)
				if(empty($options["CategoryParent"]) || !in_array((string)$category->CategoryID, $options["CategoryParent"]))
				$categories[$category->CategoryID] = array(
					'id' => $category->CategoryID,
					'level' => $category->CategoryLevel,
					'name' => $category->CategoryName,
					'parent' => $category->CategoryParentID->offsetGet(0),
					);
		}
		else
		{
			show_response_errors($response);		
		}

		return $categories;
    }, 1*7*86400, $options);
}

$root_categories = get_categories(array('LevelLimit' => 1)); // CategoryParent => 
$sub_categories = get_categories(array('LevelLimit' => 2, "CategoryParent" => array('20081'))); // CategoryParent => 

?>

<div id="categories_tree">
	<div class="control-group">
		<label class="control-label" for="searchField">Category</label>
			<select class="category" level="1" id="category_1" onchange="category_changed(this)">
			  <? foreach($root_categories as $cat) { ?>
			  	<option value="<? echo $cat['id']; ?>"> <? echo htmlentities($cat['name']); ?> </option>
			  <? } ?>
			</select>
	</div>
</div>

<div class="control-group">
  <label class="control-label" for="searchField">CategoryId</label>
  <div class="controls">
    <input id="CategoryId" name="category_id" placeholder="CategoryId" class="input-large saveable" type="text" onchange="change(this)">
  </div>
</div>

<script>
	$(document).ready(function(){
		$('.saveable').each(function(id, item){
			load_default(item.id)
		})
	})

	function load_default(name)
	{
		value = localStorage[name] || document.getElementById(name).getAttribute("default");
		if(value)
			$("#" + name).val(value);
	}

	function save_default(name, value)
	{
		localStorage[name] =  value;	
	}

	function change(item)
	{
		save_default(item.id, $(item).val())
	}

	function category_changed(el)
	{

	}

</script>


