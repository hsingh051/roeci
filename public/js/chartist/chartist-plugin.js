/* Election Material Chart */

var data1 = {
  labels: ['Recieved', 'Pending'],
  series: [60, 40]
};

var options1 = {
  labelInterpolationFnc: function(value) {
    return value[0]
  }
};

var responsiveOptions1 = [
  ['screen and (min-width: 640px)', {
    chartPadding: 30,
    labelOffset: 100,
    labelDirection: 'explode',
    labelInterpolationFnc: function(value) {
      return value;
    }
  }],
  ['screen and (min-width: 1024px)', {
    labelOffset: 80,
    chartPadding: 20
  }]
];

new Chartist.Pie('#election-material-chart', data1, options1, responsiveOptions1);


/* Polling Station Chart */

var data2 = {
  labels: ['VPS', 'CPS', 'APS', 'NPS', 'MPS'],
  series: [20, 30, 15, 10, 25]
};

var options2 = {
  labelInterpolationFnc: function(value) {
    return value[0]
  }
};

var responsiveOptions2 = [
  ['screen and (min-width: 640px)', {
    chartPadding: 30,
    labelOffset: 100,
    labelDirection: 'explode',
    labelInterpolationFnc: function(value) {
      return value;
    }
  }],
  ['screen and (min-width: 1024px)', {
    labelOffset: 85,
    chartPadding: 20
  }]
];

new Chartist.Pie('#polling-station-chart', data2, options2, responsiveOptions2);

/* ======================================================================
Overlapping Bars
====================================================================== */
var data3 = {
  labels: ['Type1', 'Type2', 'Type3', 'Type4'],
  series: [
    [5, 4, 3, 7]
  ]
};

var options3 = {
  seriesBarDistance: 10
};

var responsiveOptions3 = [
  ['screen and (max-width: 640px)', {
    seriesBarDistance: 5,
    axisX: {
      labelInterpolationFnc: function (value) {
        return value[0];
      }
    }
  }]
];

new Chartist.Bar('#request-permission-chart', data3, options3, responsiveOptions3);