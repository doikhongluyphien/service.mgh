<div class="h-content">
    <div style="clear: both"></div>
    <div class="h-list">
        <div style="font-size: 13px; font-weight: bold; margin-top: 10px;">Lịch Sử Thách Đấu</div>
        <table id="customers" class="table-role">
            <tr>               
                <th>Thời gian</th>
                <th>Chọn</th>               
                <th>Kết Quả</th>
            </tr>
            <?php                                 
            foreach ($lichsu_play as $key => $value) {                                    
            ?>
            <tr>               
                <td><?php $date = new DateTime($value["play_date"]); echo $date->format('d-m-Y H:i:s'); ?></td>
                <td><img style="width: 40px;" src="/mgh2/assets_dev/events/keobuabao/images/<?php echo $value["type_choose_play"]; ?>.png"></td>               
                <td><?php if ($value["play_status"] == 1) { ?>
                        <span style="font-weight: bold; color: #053cf7">Thắng: <?php $win_point = ($value["point_bonus"]/100)*90; echo $win_point; ?></span>
                        <div><a href="javascript:void(0);" onclick="play_details(<?php echo $value["id"]; ?>)">[Xem Chi tiết]</a></div>
                        <?php } else if ($value["play_status"] == 2) { ?>
                        <span style="font-weight: bold; color: #f79646">Hòa</span>
                        <div><a href="javascript:void(0);" onclick="play_details(<?php echo $value["id"]; ?>)">[Xem Chi tiết]</a></div>
                        <?php } else if ($value["play_status"] == 3) { ?>
                        <span style="font-weight: bold; color: #af1318">Thua: <?php echo $value["point_bonus"]; ?> NL</span>
                        <div><a href="javascript:void(0);" onclick="play_details(<?php echo $value["id"]; ?>)">[Xem Chi tiết]</a></div>                       
                        <?php } else { ?>
                        <span style="font-weight: bold; color: #af1318">Unknow</span>
                        <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>        
    </div>
</div>
