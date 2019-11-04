<?php 

if( !empty($file_data_arr) ){

?>

<div class="csv_values">
	<form>
		<table>
			<tr>
			<?php
			for ($i = 1; $i < count($file_data_arr); $i++) {
				foreach ($drodpown_table_fields as $drodpown_table_field) {
			?>
				<th>
					<select>
						<option value="<?php echo $drodpown_table_field; ?>"><?php echo $drodpown_table_field; ?></option>
					</select>
				</th>
			<?php 
				}
			}
			?>
			</tr>	
			<tr>
		<?php

			for ($i = 1; $i < count($file_data_arr); $i++) {

				$csv_line = str_getcsv( $file_data_arr[$i] );

				echo "<td>sss</td>";
			}

		?>
			</tr>
		</table>
	</form>
</div>

<?php
}