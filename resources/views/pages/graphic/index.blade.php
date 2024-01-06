@extends('adminlte::page')

@section('content')
    <div class="container-cluid pt-4">
        <x-adminlte-card title="Graphics">
            <div style="position: relative; height:60vh; width:100%">
                <canvas id="myChart"></canvas>
            </div>
        </x-adminlte-card>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '{{ route('chart.kp') }}',
                method: 'GET',
                success: function(data) {
                    let chartData = processData(data);

                    let ctx = $("#myChart");

                    let barGraph = new Chart(ctx, {
                        type: 'bar',
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                }
                            }
                        }
                    });
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });

            function processData(data) {
                let formattedData = {};

                data.forEach(item => {
                    if (!formattedData[item.item]) {
                        formattedData[item.item] = {
                            labels: [],
                            data: []
                        };
                    }

                    formattedData[item.item].labels.push(item.etd);
                    formattedData[item.item].data.push(item.total_qty);
                });

                let chartData = {
                    labels: [],
                    datasets: []
                };

                for (const itemCode in formattedData) {
                    chartData.labels = Array.from(new Set([...chartData.labels, ...formattedData[itemCode]
                        .labels
                    ]));

                    let dataset = {
                        label: 'Item ' + itemCode,
                        data: Array(chartData.labels.length).fill(0),
                        backgroundColor: 'rgba(' + randRGB() + ', 0.2)',
                        borderColor: 'rgba(' + randRGB() + ', 1)',
                        borderWidth: 1
                    };

                    formattedData[itemCode].labels.forEach((label, index) => {
                        let labelIndex = chartData.labels.indexOf(label);
                        dataset.data[labelIndex] += formattedData[itemCode].data[index];
                    });

                    chartData.datasets.push(dataset);
                }

                return chartData;
            }

            function randRGB() {
                return Math.floor(Math.random() * 256) + ',' +
                    Math.floor(Math.random() * 256) + ',' +
                    Math.floor(Math.random() * 256);
            }
        });
    </script>
@endsection
