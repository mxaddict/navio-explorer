<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div id="chart1" class="w-full" style="min-height: 365px;width:100%"></div>
<div id="chart2" class="w-full" style="min-height: 365px;width:100%"></div>
<div id="chart3" class="w-full" style="min-height: 365px;width:100%"></div>
<?php
$sql="
select
    height,
    json_extract(blks.data, '$.size') as size,
    json_extract(blks.data, '$.time') as time,
    json_extract(blks.data, '$.difficulty') as difficulty
from blks
order by id desc
limit 1000";
$q=$GLOBALS['dbh']->prepare($sql);
$q->execute();
$arr_sizes = [];
$arr_times = [];
$arr_difficulties = [];
$arr_heights = [];
$previousBlockTime = 0;
while($row=$q->fetch(PDO::FETCH_ASSOC)) {
    $arr_sizes[]=$row["size"];
    if ($previousBlockTime) {
        $arr_times[]=($previousBlockTime-$row["time"]);
    }
    $arr_difficulties[]=$row["difficulty"];
    $arr_heights[]=$row["height"];
    $previousBlockTime=$row["time"];
}
?>
<script>
  var options = {
    series: [{
      name: "Sizes",
      data: [<?php echo implode(",",$arr_sizes)?>]
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
      labels:[<?php echo implode(",",$arr_heights)?>],
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
      data: [<?php echo implode(",",$arr_difficulties)?>]
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
    labels:[<?php echo implode(",",$arr_heights)?>],
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
<script>
  var options = {
    series: [{
      name: "Block Time",
      data: [<?php echo implode(",",$arr_times)?>]
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
      text: 'Block Times',
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
    labels:[<?php echo implode(",",$arr_heights)?>],
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
var chart = new ApexCharts(document.querySelector("#chart3"), options);
chart.render();
</script>
