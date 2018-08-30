<?php
	echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
		
	echo ! empty($pagination) ? '<div id="pagination">' . $pagination . '</div>' : '';
	
	echo "<div id='search'><form method='' action='$form_cari'>";
	echo form_dropdown("q",$option_q,''); 
	echo "<input name='s' type='text' size='20' placeholder='Search' />";
	echo form_submit('', ' CARI ');
	echo "</form></div>";
	
	echo "<table width='100%'>
	 	<tr>
          	  <th>No</th>
			  <th>Nama UPB</th>
		</tr>";
  $i= $offset + 1;
  foreach ($query->result() as $row){
	echo "<tr id='listhapus_$i'>
			<td>$i</td>
            <td width='95%'><a href=".CURRENT_url()."/upb/".$row->Kd_UPB.">$row->Nm_UPB</a></td>
			</tr>";
	 $i++;
	}
	echo "</table>";
	
	if ( ! empty($link))
	{
		echo '<p id="bottom_link">';
		foreach($link as $links)
		{
			echo $links . ' ';
		}
		echo '</p>';
	}