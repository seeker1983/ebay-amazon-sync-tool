<?php
require_once 'redirect.php';
require_once 'inc.db.php';
require_once 'head.php';
session_start();
$active_user =$_SESSION['user_id'];


if (isset($_POST['sub'])) {

    $dispatch_time_max = $_POST['dispatch_time_max'];
    $title_prefix = $_POST['title_prefix'];
    $listing_duration = $_POST['listing_duration'];
    $max_quantity = $_POST['max_quantity'];
    $condition_id = $_POST['condition_id'];
    $profit_percentage = $_POST['profit_percentage'];
    $listing_type = $_POST['listing_type'];
    $price_formula = $_POST['price_formula'];
    $refund_option = $_POST['refund_option'];
    $return_accept_option = $_POST['return_accept_option'];
    $return_within = $_POST['return_within'];
    $shipping_service = $_POST['shipping_service'];
    $shipping_type = $_POST['shipping_type'];
    $shipping_cost = $_POST['shipping_cost'];
    $payment_method = $_POST['payment_method'];
    $paypal_email = $_POST['paypal_email'];
    $postal_code = $_POST['postal_code'];
    $template_code = $_POST['temp_code'];
	$sku= $_POST['sku'];

    $is_insertable = true;
    if (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]{1})$/', $dispatch_time_max))
        $is_insertable = false;
    elseif (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]{1}|[1-9]{1}[0-9]{1}[0-9]{1})$/', $max_quantity))
        $is_insertable = false;
    elseif (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]{1}|([0-9]{1}|[1-9]{1}[0-9]{1})\.[0-9]{1,2})$/', $profit_percentage))
        $is_insertable = false;
    elseif (!preg_match('/^([0-9]{1}|[1-9]{1}[0-9]{1}|([0-9]{1}|[1-9]{1}[0-9]{1})\.[0-9]{1,2})$/', $shipping_cost))
        $is_insertable = false;

    if (!strlen(trim($title_prefix)) or 
        !strlen(trim($listing_duration)) or 
        !strlen(trim($condition_id)) or
        !strlen(trim($listing_type)) or
        !strlen(trim($price_formula)) or
        !strlen(trim($refund_option)) or 
        !strlen(trim($return_accept_option)) or 
        !strlen(trim($return_within)) or
        !strlen(trim($shipping_service)) or
        !strlen(trim($shipping_type)) or 
        !strlen(trim($payment_method)) or 
        !strlen(trim($paypal_email)) or
        !strlen(trim($postal_code)) 
        )
        $is_insertable = false;

    if ($is_insertable) {

        $sql_insert = "UPDATE ebay_config SET
            title_prefix = '$title_prefix',
            max_quantity = $max_quantity,
			sku='$sku',
            profit_percentage = $profit_percentage,
            price_formula = '$price_formula',
            dispatch_time=$dispatch_time_max,
            listing_duration ='$listing_duration',
            condition_id ='$condition_id',
            refund_option = '$refund_option',
            return_accept_option = '$return_accept_option',
            return_days = '$return_within',
            shipping_service = '$shipping_service',
            shipping_type ='$shipping_type',
            shipping_cost = $shipping_cost,
            payment_method = '$payment_method',
            paypal_address = '$paypal_email', 
            listing_type = '$listing_type',
            postal_code = '$postal_code'
            WHERE
            user_id = $active_user
            ";
     
        mysql_query($sql_insert) or die('Something Gone Wrong...!');

        $template_file = dirname(__FILE__) . "/templates/template_user_" . $active_user . ".txt";
        if (trim($template_code) != '' or empty($template_code) or is_null($template_code))
            file_put_contents($template_file, trim($template_code)) or die('Something Gone Wrong...!');
    }
}

$sql_select = "SELECT * FROM ebay_config WHERE user_id=$active_user";
$rs_select = mysql_query($sql_select) or die('Something Gone Wrong....!');
if (mysql_num_rows($rs_select) != 1)
    die('Something Gone Wrong...!');

$row_show = mysql_fetch_assoc($rs_select);
?>

<body>
    <div class="page-header" style="background-color:#000000;">
        <h2 style="text-align:center; color:#FFFFFF; margin-top:0px; padding-top:20px; font-family:Georgia, 'Times New Roman', Times, serif;">Ebay - Amazon Tool</h2>
        <p style="color:#FFF; margin:auto; text-align:center; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;"><?php
            echo 'Hello ';
            if (isset($_SESSION['username'])) {
                ?>
                <strong><a href="profile.php"><?php echo $_SESSION['username']; ?></a></strong>
                <?php
            } else {
                echo 'Admin!';
            }
            ?> | <a href="logout.php" style="color:#FFFFFF; font-weight:bold;">Logout</a></p>
    </div>
    <div class="navbar">
        <div class="navbar-inner"> <a class="brand" href="dashboard.php">Ebay - Amazon Tool</a>
        </div>
    </div>

    <div id="ShowResults" style="margin:auto; width:98%;">
        <div style="height:50px;">
            <a href="add_asin.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-info" type="button">Add Asin</button></a>  
            <a href="grab_amazon.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-success" data-loading-text="Loading..." type="button">Fetch Asin Details</button></a>
            <a href="ebay_edit.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-inverse disabled" type="button">Edit Ebay Settings</button></a>
            <a href="send_to_ebay.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-danger" type="button">Add to Ebay</button></a>
            <a href="view_ebay_data.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-warning" type="button">View Ebay Listings</button></a>

        </div>
        <div style="clear:both;"></div>

        <?php
        if (isset($$message)) {
            echo $$message;
        }
        ?>
    </div>

    <div style="margin-left:30px;margin-right:30px">   

        <h3> eBay Required Fields </h3>
        <form  method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-horizontal">
            <table class="table table-bordered" width="80%" align="center">

                <tr>
                    <th width="15%">Dispatch Time Max</th>
                    <td width="25%"><input type="text" name="dispatch_time_max" class="span1" value="<?php echo $row_show['dispatch_time']; ?>" required></td>
                    <th width="15%">Title Prefix</th>
                    <td width="25%"><input type="text" name="title_prefix" class="span1" value="<?php echo $row_show['title_prefix']; ?>" required></td>
                </tr>

                <tr>
                    <th width="15%">Listing Duration</th>
                    <td width="25%"><select class="span2" name="listing_duration">
                            <option value="Days_1" <?php if ($row_show['listing_duration'] == 'Days_1') echo 'selected'; ?>> 1 DAY </option>
                            <option value="Days_3" <?php if ($row_show['listing_duration'] == 'Days_3') echo 'selected'; ?>> 3 DAYS </option>
                            <option value="Days_5" <?php if ($row_show['listing_duration'] == 'Days_5') echo 'selected'; ?>> 5 DAYS </option>
                            <option value="Days_7" <?php if ($row_show['listing_duration'] == 'Days_7') echo 'selected'; ?>> 7 DAYS </option>
                            <option value="Days_10" <?php if ($row_show['listing_duration'] == 'Days_10') echo 'selected'; ?>> 10 DAYS </option>
                            <option value="Days_14" <?php if ($row_show['listing_duration'] == 'Days_14') echo 'selected'; ?>> 14 DAYS </option>
                            <option value="Days_21" <?php if ($row_show['listing_duration'] == 'Days_21') echo 'selected'; ?>> 21 DAYS </option>
                            <option value="Days_30" <?php if ($row_show['listing_duration'] == 'Days_30') echo 'selected'; ?>> 30 DAYS </option>
                            <option value="Days_60" <?php if ($row_show['listing_duration'] == 'Days_60') echo 'selected'; ?>> 60 DAYS </option>
                            <option value="Days_90" <?php if ($row_show['listing_duration'] == 'Days_90') echo 'selected'; ?>> 90 DAYS </option>
                            <option value="GTC" <?php if ($row_show['listing_duration'] == 'GTC') echo 'selected'; ?>> GTC </option>
                        </select></td>
                    <th width="15%">Quantity</th>
                    <td width="25%"><input type="text" name="max_quantity" class="span1" value="<?php echo $row_show['max_quantity']; ?>" required></td>
                </tr>

                <tr>
                    <th width="15%">Condition Id</th>
                    <td width="25%">
                        <select class="span2" name="condition_id">
                            <option value="1000" <?php if ($row_show['condition_id'] == 1000) echo 'selected'; ?>> NEW </option>
                            <option value="3000" <?php if ($row_show['condition_id'] == 3000) echo 'selected'; ?>> USED </option>
                            <option value="4000" <?php if ($row_show['condition_id'] == 4000) echo 'selected'; ?>> Very Good </option>
                            <option value="5000" <?php if ($row_show['condition_id'] == 5000) echo 'selected'; ?>> Good </option>
                        </select>
                    </td>
                    <th width="15%">Profit</th>
                    <td width="25%"><input type="text" name="profit_percentage" class="span1" value="<?php echo $row_show['profit_percentage']; ?>" required></td>
               
			 
				</tr>


                <tr>
                    <th width="15%">Listing Type</th>
                    <td width="25%"><select class="span2" name="listing_type">
                            <option value="FixedPriceItem"> Fixed Price Item </option>
                        </select>
                    </td>
                    <th width="15%">Price Formula</th>
                    <td width="25%"><select class="span2" name="price_formula">
                            <option value="001" <?php if(trim($row_show['price_formula'])=="001") echo 'selected';?>>Basic Profit Percentage</option>
                            <option value="002" <?php if(trim($row_show['price_formula'])=="002") echo 'selected';?>>Basic Amount Profit</option>
                            <option value="003" <?php if(trim($row_show['price_formula'])=="003") echo 'selected';?>>Formula Profit Percentage</option>
                            <option value="004" <?php if(trim($row_show['price_formula'])=="004") echo 'selected';?>>Formula Amount Profit</option>
                        </select></td>
                </tr>

                <tr>
                    <th width="15%">Refund Option</th>
                    <td colspan="3"><select class="span2" name="refund_option">
                            <option value="MoneyBack" <?php if ($row_show['refund_option'] == 'MoneyBack') echo 'selected'; ?>> Money Back</option>
                            <option value="Exchange" <?php if ($row_show['refund_option'] == 'Exchange') echo 'selected'; ?>> Exchange</option>
                            <option value="MoneyBackOrExchange" <?php if ($row_show['refund_option'] == 'MoneyBackOrExchange') echo 'selected'; ?>> Money Back or Exchange</option>
                        </select></td>

                </tr>

                <tr>
                    <th width="15%">Return Accept Option</th>
                    <td colspan="3"><select class="span2" name="return_accept_option">
                            <option value="ReturnsAccepted" <?php if ($row_show['return_accept_option'] == 'ReturnsAccepted') echo 'selected'; ?>> Returns Accepted</option>
                            <option value="ReturnsNotAccepted" <?php if ($row_show['return_accept_option'] == 'ReturnsNotAccepted') echo 'selected'; ?>> Returns Not Accepted</option>
                            </select></td>
                </tr>

                <tr>
                    <th width="15%">Return Days</th>
                    <td colspan="3"><select class="span2" name="return_within">
                            <option value="Days_3" <?php if ($row_show['return_days'] == 'Days_3') echo 'selected'; ?>> 3 DAYS </option>
                            <option value="Days_7" <?php if ($row_show['return_days'] == 'Days_7') echo 'selected'; ?>> 7 DAYS </option>
                            <option value="Days_10" <?php if ($row_show['return_days'] == 'Days_10') echo 'selected'; ?>> 10 DAYS </option>
                            <option value="Days_14" <?php if ($row_show['return_days'] == 'Days_14') echo 'selected'; ?>> 14 DAYS </option>
                            <option value="Days_30" <?php if ($row_show['return_days'] == 'Days_30') echo 'selected'; ?>> 30 DAYS </option>
                            <option value="Days_60" <?php if ($row_show['return_days'] == 'Days_60') echo 'selected'; ?>> 60 DAYS </option>
                        </select></td>

                </tr>

                <tr>
                    <th width="15%">Shipping Service</th>
                    <td colspan="3"><input type="text" class="span2" name="shipping_service" value="<?php echo $row_show['shipping_service']; ?>" required></td>

                </tr>

                <tr>
                    <th width="15%">Shipping Type</th>
                    <td colspan="3"><select class="span2" name="shipping_type" value="<?php echo $row_show['shipping_type']; ?>">
                            <option value="Flat"> Flat </option>
                        </select></td>

                </tr>

                <tr>
                    <th width="15%">Shipping Cost</th>
                    <td colspan='3'><input class="span1" type="text" name="shipping_cost" value="<?php echo $row_show['shipping_cost']; ?>"</td>

                </tr>

                <tr>
                    <th width="15%">Payment Method</th>
                    <td colspan='3'><select class="span2" name="payment_method">
                            <option value="PayPal"> PayPal </option>
                        </select></td>

                </tr>

                <tr>
                    <th width="15%">Paypal email</th>
                    <td colspan='3'><input type="email" class="span3" name="paypal_email" value="<?php echo $row_show['paypal_address']; ?>" required /></td>

                </tr>

                <tr>
                    <th width="15%">Postal Code</th>
                    <td colspan='3'><input type="text" class="span2" name="postal_code" value="<?php echo $row_show['postal_code']; ?>" required /></td>

                </tr>

                <tr>
                    <th width="15%">Template Code</th>
                    <td colspan='3'><textarea name="temp_code" cols="50" rows="10"><?php
                            $template_file = dirname(__FILE__) . "/templates/template_user_" . $active_user . ".txt";
                            if (file_exists($template_file))
                                echo file_get_contents($template_file);
                            elseif (!file_exists($template_file))
                                echo '';
                            ?> </textarea></td>

                </tr>
				<tr>
                 <th width="15%">SKU</th>
                    <td width="40%"><input type="text" name="sku" class="span2" value="<?php echo $row_show['sku']; ?>" required></td>
                </tr>
                <tr>
                    <th colspan="4" align="center"><input type="submit" name="sub" value="Add values" class="btn btn-success"></th>
                </tr>

            </table>

        </form>
