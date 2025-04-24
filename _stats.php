<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<div id="chart1" class="w-full" style="min-height: 365px;width:100%"></div>
<div id="chart2" class="w-full" style="min-height: 365px;width:100%"></div>
<div id="chart3" class="w-full" style="min-height: 365px;width:100%"></div>
<?
$sql="SELECT block_id,JSON_EXTRACT(blocks.data, '$.size') AS size,JSON_EXTRACT(blocks.data, '$.time') AS time,JSON_EXTRACT(blocks.data, '$.difficulty') AS difficulty FROM blocks WHERE network_id=:network_id ORDER BY id DESC LIMIT 1000";
$q=$GLOBALS['dbh']->prepare($sql);
$q->bindParam(':network_id',$GLOBALS["network_id"], PDO::PARAM_INT);
$q->execute();
if ($q->rowCount()>0)
{
  while($row=$q->fetch(PDO::FETCH_ASSOC))
  {
    $arr_sizes[]=$row["size"];
    if ($previousBlockTime) $arr_times[]=($previousBlockTime-$row["time"]);
    $arr_difficulties[]=$row["difficulty"];
    $arr_block_ids[]=$row["block_id"];
    $previousBlockTime=$row["time"];
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
<script>
  var options = {
    series: [{
      name: "Block Time",
      data: [<?=implode(",",$arr_times)?>]
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
var chart = new ApexCharts(document.querySelector("#chart3"), options);
chart.render();
</script>