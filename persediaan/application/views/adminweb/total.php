<script type="text/javascript">
function rn(){
        return (Math.floor( Math.random()* (1+40-20) ) ) + 20;
}
$(function () {

        kib_a = <?php echo json_encode($kiba); ?>;
        $(".total_kiba").sparkline(kib_a,{
            type: 'line',
            width: '100%',
            height: 50,
            fillColor: '#87D493',
            spotRadius: 4
        });

        kib_b = <?php echo json_encode($kibb); ?>;
        $(".total_kibb").sparkline(kib_b,{
            type: 'line',
            /*tooltipSuffix: " Total",*/
            width: '100%',
            height: 50,
            fillColor: '#87D493',
            spotRadius: 4
        });

        kib_c = <?php echo json_encode($kibc); ?>;
        $(".total_kibc").sparkline(kib_c,{
            type: 'line',
            width: '100%',
            height: 50,
            fillColor: '#87D493',
            spotRadius: 4
        });

        kib_d = <?php echo json_encode($kibd); ?>;
        $(".total_kibd").sparkline(kib_d,{
            type: 'line',
            width: '100%',
            height: 50,
            fillColor: '#87D493',
            spotRadius: 4
        });

        kib_e = <?php echo json_encode($kibe); ?>;
        $(".total_kibe").sparkline(kib_e,{
            type: 'line',
            width: '100%',
            height: 50,
            fillColor: '#87D493',
            spotRadius: 4
        });

        kib_f = <?php echo json_encode($kibf); ?>;
        $(".total_kibf").sparkline(kib_f,{
            type: 'line',
            width: '100%',
            height: 50,
            fillColor: '#87D493',
            spotRadius: 4
        });
    
});
</script>   
<div class="row">
            <div class="span2">

                <div class="panel">
                    <div class="panel-header"><i class="icon-bar-chart"></i> KIB A</div>
                    <div class="panel-content">
                        <div style="text-align:center">
                            <span class=""><strong><?php echo rp(array_sum($kiba)); ?></strong></span><br />
                            <span class="total_kiba"></span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="span2">

                <div class="panel">
                    <div class="panel-header"><i class="icon-bar-chart"></i> KIB B</div>
                    <div class="panel-content">
                        <div style="text-align:center">
                            <span class=""><strong><?php echo rp(array_sum($kibb)); ?></strong></span><br />
                            <span class="total_kibb"></span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="span2">

               <div class="panel">
                    <div class="panel-header"><i class="icon-bar-chart"></i> KIB C</div>
                    <div class="panel-content">
                        <div style="text-align:center">
                            <span class=""><strong><?php echo rp(array_sum($kibc)); ?></strong></span><br />
                            <span class="total_kibc"></span>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="span2">

               <div class="panel">
                    <div class="panel-header"><i class="icon-bar-chart"></i> KIB D</div>
                    <div class="panel-content">
                        <div style="text-align:center">
                            <span class=""><strong><?php echo rp(array_sum($kibd)); ?></strong></span><br />
                            <span class="total_kibd"></span>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="span2">

                <div class="panel">
                    <div class="panel-header"><i class="icon-bar-chart"></i> KIB E</div>
                    <div class="panel-content">
                        <div style="text-align:center">
                            <span class=""><strong><?php echo rp(array_sum($kibe)); ?></strong></span><br />
                            <span class="total_kibe"></span>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="span2">

                <div class="panel">
                    <div class="panel-header"><i class="icon-bar-chart"></i> KIB F</div>
                    <div class="panel-content">
                        <div style="text-align:center">
                            <span class=""><strong><?php echo rp(array_sum($kibf)); ?></strong></span><br />
                            <span class="total_kibf"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>