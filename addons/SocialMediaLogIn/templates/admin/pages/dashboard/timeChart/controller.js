{literal}

var Time_Chart = 
{
    init: function()
    {
        $("#timeChart .tableView").hide();
        JSONParser.request("getTimeChartData", {null: null}, function(result) 
        {
            Time_Chart.setChartData(JSON.parse(result));
        });
    },

    setChartData: function(values) 
    {       
        var data = values;
        var dataValue = [];
        
        var container = $("#timeChart-income-chart canvas"); 
        new Chart(container, {
            type: "bar",
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                        },
                        ticks: {
                            stepSize: values.stepSizeY,   
                            beginAtZero: true,             
                        }  
                    }]
                }
            }
        });
    }, 
}
Time_Chart.init();
{/literal}