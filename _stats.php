<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div id="chart1" class="w-full" style="min-height: 365px;width:100%"></div>
<div id="chart2" class="w-full" style="min-height: 365px;width:100%"></div>
<?php
$sql="
select
    block_id,
    json_extract(blocks.data, '$.size') as size,
    json_extract(blocks.data, '$.time') as time,
    json_extract(blocks.data, '$.difficulty') as difficulty
from blocks
order by id desc
limit 1000";
$q=$GLOBALS['dbh']->prepare($sql);
$q->execute();
if ($q->rowCount()>0)
{
  $arr_sizes = [];
  $arr_times = [];
  $arr_difficulties = [];
  $arr_block_ids = [];
  while($row=$q->fetch(PDO::FETCH_ASSOC))
  {
    $arr_sizes[]=$row["size"];
    $arr_times[]=$row["time"];
    $arr_difficulties[]=$row["difficulty"];
    $arr_block_ids[]=$row["block_id"];
  }
}
?>
<script>
  var options = {
    series: [{
      name: "Sizes",
      data: [<?=implode(",",$arr_sizes)?>]
    }],
    chart: {
      type: 'area',
      height: 350,
      zoom: {
        enabled: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight'
    },
    title: {
      text: 'Block Size',
      align: 'left',
      style: {
        fontSize:  '12pt',
        fontWeight:  'bold',
        color:  '#ffffff'
      },        },
      subtitle: {
        text: 'Latest 1000 Block',
        align: 'left',
        style: {
          fontSize:  '12pt',
          fontWeight:  'bold',
          color:  '#ffffff'
        },
      },
      labels:[<?=implode(",",$arr_block_ids)?>],
      xaxis: {
       labels: {
        show: false,
      }
    },
    yaxis: {
      opposite: true
    },
    legend: {
      horizontalAlign: 'left',
      colors: 'white',
      useSeriesColors: false
    }
  };

  var chart = new ApexCharts(document.querySelector("#chart1"), options);
  chart.render();
</script>
<script>
  var options = {
    series: [{
      name: "Difficulty",
      data: [<?=implode(",",$arr_difficulties)?>]
    }],
    chart: {
      type: 'area',
      height: 350,
      zoom: {
        enabled: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight'
    },
    title: {
      text: 'Block difficulty',
      align: 'left',
      style: {
        fontSize:  '12pt',
        fontWeight:  'bold',
        color:  '#ffffff'
      },
    },
    subtitle: {
      text: 'Latest 1000 Block',
      align: 'left',
      style: {
        fontSize:  '12pt',
        fontWeight:  'bold',
        color:  '#ffffff'
      },
    },
    labels:[<?=implode(",",$arr_block_ids)?>],
    xaxis: {
     labels: {
      show: false,
    }
  },
  yaxis: {
    opposite: true
  },
  legend: {
    horizontalAlign: 'left',
    colors: 'white',
    useSeriesColors: false
  }
};
var chart = new ApexCharts(document.querySelector("#chart2"), options);
chart.render();
</script>
