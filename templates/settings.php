<?php 
// require_once(  __DIR__  . '/../classes/feed.class.php' );

// $a = new Feed();
// $a->getFeed( 'http://casino.everymatrix.com/jsonfeeds/mix/maxbet_com?types=game' );
/*
	// $wpfeedcrud = new WpFeedCRUD();
	// $wpfeedcrud->importData( 'http://casino.everymatrix.com/jsonfeeds/mix/maxbet_com?types=game' );

*/


?>
<div class="wrap">
    <h2>WP Feed CRUD</h2>
    <form id="trigger_import" method="post" action=""> 
        <table class="form-table">  
            <tr valign="top">
                <td scope="row">
                    <div class="ajax_row">Start your feed import</div>
                    <div class="animation" style="display: none">
                        <img src="<?php echo plugins_url( '../assets/img/loading.gif', __FILE__ ) ?>" width="90" height="90">
                    </div>

                </td>
                <td><input type="hidden" name="import" id="import" value="1" /></td>
            </tr>
        </table>
        <input type="submit" value="Start import">
    </form>
</div>