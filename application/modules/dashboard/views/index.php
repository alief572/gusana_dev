<div class="br-pagetitle">
    <i class="icon icon ion-ios-photos-outline"></i>
    <div>
        <h4>Dashboard Cards</h4>
        <p class="mg-b-0">Dashboard statistic and monitoring data.</p>
    </div>
</div><!-- d-flex -->

<div class="br-pagebody pd-x-20 pd-sm-x-30">
    <?php echo Template::message(); ?>
    <div class="row row-sm mg-t-20 mg-b-20">
        <div class="col-sm-6 col-lg-4">
            <div class="bg-white rounded bd bd-gray-400 overflow-hidden">
                <div class="pd-x-20 pd-t-20 d-flex align-items-center">
                    <i class="ion ion-earth tx-80 lh-0 tx-primary op-5"></i>
                    <!-- <i class="fa fa-list-ul tx-80 lh-0 tx-primary op-5" aria-hidden="true"></i> -->
                    <div class="mg-l-20">
                        <p class="tx-10 tx-spacing-2 tx-mont tx-semibold tx-uppercase mg-b-10">Today's
                            Request</p>
                        <h3 class="tx-48 tx-inverse tx-lato tx-black mg-b-0 lh-1"><?= ($totalRequest) ?: 0; ?></h3>
                        <span class="tx-12 tx-roboto tx-gray-600">Requests</span>
                    </div>
                </div>
                <div id="ch5" class="ht-60 tr-y-1 rickshaw_graph">
                    <svg width="522" height="60">
                        <g>
                            <path d="M0,30Q37.7,25.75,43.5,26.25C52.199999999999996,27,78.3,37.125,87,37.5S121.8,31.5,130.5,30S165.3,22.5,174,22.5S208.79999999999998,27.75,217.49999999999997,30S252.3,42.75,261,45S295.79999999999995,52.5,304.49999999999994,52.5S339.3,46.125,348,45S382.8,42.375,391.5,41.25S426.29999999999995,33.375,434.99999999999994,33.75S469.8,45.375,478.5,45Q484.3,44.75,522,30L522,60Q484.3,60,478.5,60C469.8,60,443.69999999999993,60,434.99999999999994,60S400.2,60,391.5,60S356.7,60,348,60S313.19999999999993,60,304.49999999999994,60S269.7,60,261,60S226.19999999999996,60,217.49999999999997,60S182.7,60,174,60S139.2,60,130.5,60S95.7,60,87,60S52.199999999999996,60,43.5,60Q37.7,60,0,60Z" class="area" fill="#0866C6"></path>
                        </g>
                    </svg>
                </div>
            </div>
        </div><!-- col-4 -->
        <div class="col-sm-6 col-lg-4 mg-t-20 mg-sm-t-0">
            <div class="bg-white rounded bd bd-gray-400 overflow-hidden">
                <div class="pd-x-20 pd-t-20 d-flex align-items-center">
                    <i class="ion ion-bag tx-80 lh-0 tx-purple op-5"></i>
                    <div class="mg-l-20">
                        <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase mg-b-10">Today Checked</p>
                        <p class="tx-48 tx-inverse tx-lato tx-black mg-b-0 lh-1">0</p>
                        <span class="tx-12 tx-roboto tx-gray-600">Checked</span>
                    </div>
                </div>
                <div id="ch6" class="ht-60 tr-y-1"></div>
            </div>
        </div><!-- col-4 -->
        <div class="col-sm-6 col-lg-4 mg-t-20 mg-lg-t-0">
            <div class="bg-white rounded bd bd-gray-400 overflow-hidden">
                <div class="pd-x-20 pd-t-20 d-flex align-items-center">
                    <i class="ion ion-monitor tx-80 lh-0 tx-teal op-5"></i>
                    <div class="mg-l-20">
                        <p class="tx-10 tx-spacing-1 tx-mont tx-semibold tx-uppercase mg-b-10">Today Quotation</p>
                        <p class="tx-48 tx-inverse tx-lato tx-black mg-b-0 lh-1">0</p>
                        <span class="tx-12 tx-roboto tx-gray-600">Quotations</span>
                    </div>
                </div>
                <div id="ch7" class="ht-60 tr-y-1"></div>
            </div>
        </div><!-- col-4 -->
    </div><!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="bd bg-white pd-t-10 pd-b-10 pd-x-20">
                <h4 class="mg-b-20">This Year <?= date('Y'); ?></h4>
                <canvas id="chartArea1" height="50"></canvas>
            </div>
        </div><!-- col-6 -->
    </div><!-- row -->
</div>

<script src=" <?= base_url(); ?>themes/bracket/assets/lib/chart.js/Chart.min.js"></script>
<script>
    $(function() {
        var ch5 = new Rickshaw.Graph({
            element: document.querySelector('#ch5'),
            renderer: 'area',
            max: 80,
            series: [{
                data: [{
                        x: 0,
                        y: 40
                    },
                    {
                        x: 1,
                        y: 45
                    },
                    {
                        x: 2,
                        y: 30
                    },
                    {
                        x: 3,
                        y: 40
                    },
                    {
                        x: 4,
                        y: 50
                    },
                    {
                        x: 5,
                        y: 40
                    },
                    {
                        x: 6,
                        y: 20
                    },
                    {
                        x: 7,
                        y: 10
                    },
                    // {
                    //     x: 8,
                    //     y: 20
                    // },
                    // {
                    //     x: 9,
                    //     y: 25
                    // },
                    // {
                    //     x: 10,
                    //     y: 35
                    // },
                    // {
                    //     x: 11,
                    //     y: 20
                    // },
                    // {
                    //     x: 12,
                    //     y: 40
                    // }
                ],
                color: '#0866C6'
            }]
        });
        ch5.render();

        //Responsive Mode
        new ResizeSensor($('.br-mainpanel'), function() {
            ch5.configure({
                width: $('#ch5').width(),
                height: $('#ch5').height()
            });
            ch5.render();
        });

        var ch6 = new Rickshaw.Graph({
            element: document.querySelector('#ch6'),
            renderer: 'area',
            max: 80,
            series: [{
                data: [{
                        x: 0,
                        y: 40
                    },
                    {
                        x: 1,
                        y: 45
                    },
                    {
                        x: 2,
                        y: 30
                    },
                    {
                        x: 3,
                        y: 40
                    },
                    {
                        x: 4,
                        y: 50
                    },
                    {
                        x: 5,
                        y: 40
                    },
                    {
                        x: 6,
                        y: 20
                    },
                    {
                        x: 7,
                        y: 10
                    },
                    {
                        x: 8,
                        y: 20
                    },
                    {
                        x: 9,
                        y: 25
                    },
                    {
                        x: 10,
                        y: 35
                    },
                    {
                        x: 11,
                        y: 20
                    },
                    {
                        x: 12,
                        y: 40
                    }
                ],
                color: '#6F42C1'
            }]
        });
        ch6.render();

        // Responsive Mode
        new ResizeSensor($('.br-mainpanel'), function() {
            ch6.configure({
                width: $('#ch6').width(),
                height: $('#ch6').height()
            });
            ch6.render();
        });

        var ch7 = new Rickshaw.Graph({
            element: document.querySelector('#ch7'),
            renderer: 'area',
            max: 80,
            series: [{
                data: [{
                        x: 0,
                        y: 40
                    },
                    {
                        x: 1,
                        y: 45
                    },
                    {
                        x: 2,
                        y: 30
                    },
                    {
                        x: 3,
                        y: 40
                    },
                    {
                        x: 4,
                        y: 50
                    },
                    {
                        x: 5,
                        y: 40
                    },
                    {
                        x: 6,
                        y: 20
                    },
                    {
                        x: 7,
                        y: 10
                    },
                    {
                        x: 8,
                        y: 20
                    },
                    {
                        x: 9,
                        y: 25
                    },
                    {
                        x: 10,
                        y: 35
                    },
                    {
                        x: 11,
                        y: 20
                    },
                    {
                        x: 12,
                        y: 40
                    }
                ],
                color: '#20C997'
            }]
        });
        ch7.render();

        // Responsive Mode
        new ResizeSensor($('.br-mainpanel'), function() {
            ch7.configure({
                width: $('#ch7').width(),
                height: $('#ch7').height()
            });
            ch7.render();
        });

    })

    'use strict';
    $(window).resize(function() {
        minimizeMenu();
    });

    minimizeMenu();

    function minimizeMenu() {
        if (window.matchMedia('(min-width: 992px)').matches && window.matchMedia('(max-width: 1199px)')
            .matches) {
            // show only the icons and hide left menu label by default
            $('.menu-item-label,.menu-item-arrow').addClass('op-lg-0-force d-lg-none');
            $('body').addClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideUp();
        } else if (window.matchMedia('(min-width: 1200px)').matches && !$('body').hasClass('collapsed-menu')) {
            $('.menu-item-label,.menu-item-arrow').removeClass('op-lg-0-force d-lg-none');
            $('body').removeClass('collapsed-menu');
            $('.show-sub + .br-menu-sub').slideDown();
        }
    }


    var ctx = document.getElementById('chartArea1').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        // fill: false,
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                    label: 'Requests',
                    data: [12, 39, 20, 10, 25, 18, 12, 39, 20, 10, 25, 18],
                    backgroundColor: '#0866C6'
                },
                {
                    label: 'Checked',
                    data: [10, 50, 30, 50, 35, 40, 10, 50, 30, 50, 35, 40],
                    backgroundColor: '#6F42C1'
                },
                {
                    label: 'Quotations',
                    data: [5, 10, 20, 10, 15, 10, 5, 10, 20, 10, 15, 10],
                    backgroundColor: '#20C997'
                },
            ]
        },
        options: {
            legend: {
                display: true,
                labels: {
                    display: true
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontSize: 10,
                        max: 80
                    }
                }],
                xAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontSize: 14
                    }
                }]
            }
        }
    });
</script>