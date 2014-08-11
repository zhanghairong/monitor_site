<div class="result_wrap">
    <div class="show" style="padding: 10px;">
        <div class="cell_table">
            <table style="width:80%;font-size:12px;" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th>page</th>
        			<th>num</th>
        		</tr>
                <?php 
                    $total = 0;
                    foreach($lines as $linename=>$num){
                        $total += $num;
                ?>
                <tr>
                    <td><strong><?php echo $linename;?></strong></td>
        			<td><?php echo $num;?></td>
        		</tr>
                <?php }?>
                <tr>
                    <td><strong>total</strong></td>
        			<td><?php echo $total;?></td>
        		</tr>
            </table>
        </div>
    </div>
</div>