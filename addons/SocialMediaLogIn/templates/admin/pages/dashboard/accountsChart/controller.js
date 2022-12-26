{literal}
var Accounts_Chart = 
{
    init: function()
    {
        $("#accountsChart .tableView").hide();
        JSONParser.request("getAccountsChartData", {null: null}, function(result) 
        {
            Accounts_Chart.setTableData(result);
            Accounts_Chart.setChartData(result);
        });
    },
    
    setTableData: function(data)
    {
        $.each(data, function(count, values)
        {
            var row = $('tr').clone();
            row.html(function(index, text) 
            {         
                text = text.replace(/(\+count\+)/g, values.count);
                return text;
            });

            $("#accountsChart tbody").append(row);
        });
    },

    setChartData: function(values) 
    {
        var data = {labels: [], datasets: [{data: []}]};
        var count = [];

        $.each(values, function(index, value)
        {
            if(index == 'stepSizeY'){
                    return true;
            }      
   
            data.labels.push(index);
            count.push(value.count);
        });
        
        data.datasets = [
            {
                label: "{/literal}{$MGLANG->T('NumberOfAccounts')}{literal}",
                backgroundColor: "rgba(54, 162, 235, 0.2)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 1,
                data: count,
            },  
        ];
        
        var container = $("#accountsChart-income-chart canvas"); 
        new Chart(container, {
            type: "bar",
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                   display: false,
                },
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
            },  
        });
    }
}
Accounts_Chart.init();
{/literal}

