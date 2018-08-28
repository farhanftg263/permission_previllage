<?php $this->assign('title', $title); ?>
<?php $this->assign('heading', $heading); ?>
<?php
$this->Breadcrumbs->addCrumb($heading);
?>
<div class="box-body">
    <div class="box-body">
        <div class="row">
            <div class="col-lg-4 col-xs-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>42</h3><p>Total User</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-fw fa-group"></i>
                    </div>
                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-xs-4">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>15</h3><p>Total Number</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-fw fa-group"></i>
                    </div>
                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-xs-4">
                <!-- small box -->
                <div class="small-box bg-lime">
                    <div class="inner">
                        <h3>45</h3>

                        <p>Total Count</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-fw fa-reorder"></i>
                    </div>
                    <a href="javascript:void(0)" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>           

        </div>


        <div class="box box-info">
            <div class="box-header">
                <i class="fa fa-envelope"></i>
                <h3 class="box-title">
                    Top 5 recent register users</h3>
                <!-- tools box -->
                <div class="pull-right box-tools">
                    <a href="javascript:void(0)" class="btn btn-info btn-sm" data-toggle="tooltip" title="" data-original-title="View All">
                        View All </a>
                </div>
                <!-- /. tools -->
            </div>
            <div class="box-body">
                <table class="Top5Member" border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email </th>
                            <th class="last">Status</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header">
                <i class="fa fa-fw fa-reorder"></i>
                <h3 class="box-title">
                    Top 5 recent </h3>
                <!-- tools box -->
                <div class="pull-right box-tools">
                    <a href="javascript:void(0)" class="btn btn-info btn-sm" data-toggle="tooltip" title="" data-original-title="View All">
                        View All </a>
                </div>
                <!-- /. tools -->
            </div>
            <div class="box-body">
                <table class="Top5Member" border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th class="last">Status</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </div>     
</div>
