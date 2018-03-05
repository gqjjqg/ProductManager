<?php defined('BASEPATH') OR exit('') ?>

<?= isset($range) && !empty($range) ? $range : ""; ?>
<div class="panel panel-primary">
    <!-- Default panel contents -->
    <div class="panel-heading">Product Transactions</div>
    <?php if($allItems): ?>
    <div class="table table-responsive">
        <table class="table table-bordered table-striped" style="background-color: #f5f5f5">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Priority</th>
                    <th>Platform</th>
                    <th>Status</th>
                    <th>Competitor</th>
                    <th>Project Name</th>
                    <th>Create DATE</th>
                    <th>Update DATE</th>
                    <th>DESCRIPTION</th>
                    <th>EDIT</th>
                    <th>DELETE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allItems as $get): ?>
                <tr>
                    <input type="hidden" value="<?=$get->id?>" class="curItemId">
                    <th class="itemSN"><?=$sn?>.</th>
                    <td><span id="itemID-<?=$get->id?>"><?=$get->id?></span></td>
                    <td><span id="itemProduct-<?=$get->id?>"><?=$get->ProductName?></td>
                    <td><span id="itemCustomer-<?=$get->id?>"><?=$get->CustomerName?></td>
                    <td><span id="itemPriority-<?=$get->id?>"><?=$get->PriorityName?></td>
                    <td><span id="itemPlatform-<?=$get->id?>"><?=$get->PlatformName?></td>
                    <td><span id="itemStatus-<?=$get->id?>"><?=$get->StatusName?></td>
                    <td><span id="itemCompetitor-<?=$get->id?>"><?=$get->CompetitorName?></td>
                    <td><span id="itemProject-<?=$get->id?>"><?=$get->ProjectName?></td>
                    <td><span id="itemAddDate-<?=$get->id?>"><?= date('jS M, Y h:ia', strtotime($get->AddedDate)) ?> </td>
                    <td><span id="itemUpdateDate-<?=$get->id?>"><?= date('jS M, Y h:ia', strtotime($get->UpdatedDate)) ?></td>
                    <td>
                        <span id="itemDesc-<?=$get->id?>" data-toggle="tooltip" title="<?=$get->Notes?>" data-placement="auto">
                            <?=character_limiter($get->Notes, 15)?>
                        </span>
                    </td>
                    <td class="text-center text-primary">
                        <span class="editItem" id="edit-<?=$get->id?>"><i class="fa fa-pencil pointer"></i> </span>
                    </td>
                    <td class="text-center"><i class="fa fa-trash text-danger delItem pointer"></i></td>
                </tr>
                <?php $sn++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<!-- table div end--->
    <?php else: ?>
    <ul><li>No items</li></ul>
    <?php endif; ?>
</div>
<!--- panel end-->

<!---Pagination div-->
<div class="col-sm-12 text-center">
    <ul class="pagination">
        <?= isset($links) ? $links : "" ?>
    </ul>
</div>