<?php 

if( !empty($file_data_arr) ){

	$head_line = str_getcsv( $file_data_arr[0] );
?>
<div class="csv_values">
	<div class="animation" style="display: none">
        <img src="<?php echo plugins_url( '../assets/img/loading.gif', __FILE__ ) ?>" width="90" height="90">
    </div>
	<input type="button"  value="Update Fields" id="update_fields">
	<form id="csv_values">
		<table>
			<tr>
			<?php
			for ($i = 0; $i < count($head_line); $i++) {
			
			?>
				<th>
					<select name="headcol[col<?php echo $i;?>]">
						<?php 	foreach ($drodpown_table_fields as $drodpown_table_field) { ?>
							<option value="<?php echo $drodpown_table_field; ?>"><?php echo $drodpown_table_field; ?></option>
						<?php  	} ?>
					</select>
				</th>
			<?php 
			}
			?>
			</tr>	
		<?php
			for ($i = 1; $i < count($file_data_arr); $i++) {
				?>
				<tr>
				<?php
					$csv_line = str_getcsv( $file_data_arr[$i] );

					foreach ($csv_line as $csv) {
				?>
						<td>
							<input type="hidden" name="column[][col<?php echo $i-1;?>]" value="<?php echo $csv; ?>"/>
							<?php echo $csv; ?>
						</td>
				<?php
					}
				?>
				</tr>
				<?php
			}
		?>
		</table>
	</form>
</div>

<?php
}