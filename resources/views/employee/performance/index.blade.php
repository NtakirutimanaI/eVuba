@include('layouts.header')
@include('layouts.sidebar')

<link rel="stylesheet" href="{{ asset('css/dashboard-pro.css') }}">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="dashboard-wrapper">
    <div class="pro-header">
        <div>
            <h1><i class="fas fa-chart-line" style="color: var(--primary);"></i> Performance Pulse</h1>
            <p style="color: var(--secondary); margin: 5px 0 0;">Real-time analytics and KPI tracking for your roles.</p>
        </div>
        <div class="header-actions">
            <div class="glass-panel" style="padding: 10px 20px; border-radius: 14px; text-align: center;">
                <span style="font-size: 0.7rem; color: var(--secondary); text-transform: uppercase; font-weight: 800;">Efficiency Rating</span>
                <strong style="display: block; font-size: 1.25rem; color: #10b981;">{{ $performance['tickets_total'] > 0 ? round(($performance['tickets_resolved'] / $performance['tickets_total']) * 100) : 0 }}%</strong>
            </div>
        </div>
    </div>

    {{-- Intelligence Matrix --}}
    <div class="intelligence-matrix" style="margin-top: 3rem;">
        <div class="matrix-card">
            <div class="card-glow primary"></div>
            <div class="matrix-main">
                <div class="matrix-icon"><i class="fas fa-satellite-dish"></i></div>
                <div class="matrix-data">
                    <span class="matrix-label">Service Bandwidth</span>
                    <strong class="matrix-value">{{ $performance['bookings'] }}</strong>
                </div>
            </div>
            <div class="matrix-footer">Total service cycles executed</div>
        </div>

        <div class="matrix-card">
            <div class="card-glow success"></div>
            <div class="matrix-main">
                <div class="matrix-icon"><i class="fas fa-shield-virus"></i></div>
                <div class="matrix-data">
                    <span class="matrix-label">Resolution Depth</span>
                    <strong class="matrix-value">{{ $performance['tickets_resolved'] }}</strong>
                </div>
            </div>
            <div class="matrix-footer">Successfully neutralized threats</div>
        </div>

        <div class="matrix-card">
            <div class="card-glow warning"></div>
            <div class="matrix-main">
                <div class="matrix-icon"><i class="fas fa-dna"></i></div>
                <div class="matrix-data">
                    <span class="matrix-label">Financial Yield</span>
                    <strong class="matrix-value">{{ number_format($performance['sales']) }}</strong>
                </div>
            </div>
            <div class="matrix-footer">Revenue generation coefficient</div>
        </div>

        <div class="matrix-card">
            <div class="card-glow purple"></div>
            <div class="matrix-main">
                <div class="matrix-icon"><i class="fas fa-meteor"></i></div>
                <div class="matrix-data">
                    <span class="matrix-label">Response Velocity</span>
                    <strong class="matrix-value">{{ $performance['avg_response_time'] }}h</strong>
                </div>
            </div>
            <div class="matrix-footer">Average temporal latency</div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div style="display: grid; grid-template-columns: 1.8fr 1.2fr; gap: 30px; margin-top: 3rem;">
        <div class="pro-card glass-panel" style="padding: 40px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h3 style="margin: 0; font-size: 1.4rem; font-weight: 800; color: var(--dark);">Optimization Radar</h3>
                <span class="status-pill staff">Live Analytics</span>
            </div>
            <div id="distributionChart" style="min-height: 400px;"></div>
        </div>
        
        <div class="pro-card glass-panel" style="padding: 40px; display: flex; flex-direction: column;">
            <div style="margin-bottom: 30px;">
                <h3 style="margin: 0; font-size: 1.4rem; font-weight: 800; color: var(--dark);">Target Integrity</h3>
                <p style="color: var(--secondary); margin-top: 5px;">Alignment with mission objectives</p>
            </div>
            <div style="flex: 1; display: flex; align-items: center; justify-content: center;">
                <div id="radialTargetChart" style="width: 100%;"></div>
            </div>
        </div>
    </div>

    {{-- Intelligence Briefings --}}
    <div style="margin-top: 3rem; background: var(--white); border-radius: 30px; padding: 50px; border: 1px solid var(--glass-border);">
        <h3 style="margin: 0 0 40px; font-size: 1.6rem; font-weight: 900; color: var(--dark); text-align: center;">Strategic Intel Summary</h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px;">
            <div class="intel-brief">
                <div class="intel-icon primary"><i class="fas fa-project-diagram"></i></div>
                <div class="intel-content">
                    <h4>Conversion Index</h4>
                    <p>Current trajectory indicates an engagement-to-booking ratio of <strong>{{ $performance['bookings_appointments_ratio'] }}x</strong>. Strategy optimized.</p>
                </div>
            </div>
            <div class="intel-brief">
                <div class="intel-icon success"><i class="fas fa-check-double"></i></div>
                <div class="intel-content">
                    <h4>Neutralization Rate</h4>
                    <p>Support resolution fidelity is locked at <strong>{{ $performance['tickets_total'] > 0 ? round(($performance['tickets_resolved'] / $performance['tickets_total']) * 100) : 100 }}%</strong> across all channels.</p>
                </div>
            </div>
            <div class="intel-brief">
                <div class="intel-icon warning"><i class="fas fa-tachometer-alt"></i></div>
                <div class="intel-content">
                    <h4>System Latency</h4>
                    <p>Average response pulse is holding steady at <strong>{{ $performance['avg_response_time'] }} hours</strong>. Performance within parameters.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Intelligence Dashboard Engine */
    .intelligence-matrix { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; }
    .matrix-card { background: var(--white); padding: 30px; border-radius: 24px; position: relative; overflow: hidden; border: 1px solid var(--glass-border); transition: 0.3s; }
    .matrix-card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }
    
    .card-glow { position: absolute; width: 100px; height: 100px; border-radius: 50%; top: -30px; right: -30px; filter: blur(40px); opacity: 0.15; }
    .card-glow.primary { background: var(--primary); }
    .card-glow.success { background: #10b981; }
    .card-glow.warning { background: #f59e0b; }
    .card-glow.purple { background: #8b5cf6; }

    .matrix-main { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; position: relative; z-index: 2; }
    .matrix-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: var(--light); color: var(--dark); font-size: 1.2rem; }
    .matrix-data { display: flex; flex-direction: column; }
    .matrix-label { font-size: 0.7rem; font-weight: 800; color: var(--secondary); text-transform: uppercase; letter-spacing: 0.5px; }
    .matrix-value { font-size: 1.6rem; font-weight: 900; color: var(--dark); line-height: 1; margin-top: 4px; }
    .matrix-footer { font-size: 0.75rem; color: var(--secondary); opacity: 0.6; font-weight: 600; }

    .intel-brief { display: flex; gap: 20px; align-items: flex-start; }
    .intel-icon { width: 50px; height: 50px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; }
    .intel-icon.primary { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
    .intel-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .intel-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    
    .intel-content h4 { margin: 0 0 10px; font-size: 1.1rem; font-weight: 800; color: var(--dark); }
    .intel-content p { font-size: 0.9rem; color: var(--secondary); line-height: 1.6; margin: 0; }
    .intel-content strong { color: var(--dark); font-weight: 800; }

    .status-pill.staff { background: var(--dark); color: white; padding: 6px 14px; border-radius: 10px; font-size: 0.7rem; font-weight: 800; }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Activity Distribution Chart (Radar)
        var optionsDist = {
            series: [{
                name: 'Performance Score',
                data: [
                    {{ $performance['bookings'] }}, 
                    {{ $performance['appointments'] }}, 
                    {{ $performance['tickets_resolved'] }}, 
                    {{ round($performance['sales'] / 1000) }}, 
                    {{ round($performance['avg_response_time']) }}
                ],
            }],
            chart: {
                height: 380,
                type: 'radar',
                toolbar: { show: false },
                dropShadow: { enabled: true, blur: 1, left: 1, top: 1 }
            },
            colors: ['#6366f1'],
            xaxis: {
                categories: ['Bookings', 'Appts', 'Resolved', 'Revenue (k)', 'Latency']
            },
            stroke: { width: 2 },
            fill: { opacity: 0.1 },
            markers: { size: 0 }
        };
        new ApexCharts(document.querySelector("#distributionChart"), optionsDist).render();

        // Target Achievement Chart (Radial)
        var optionsRadial = {
            series: [{{ $performance['tickets_total'] > 0 ? round(($performance['tickets_resolved'] / $performance['tickets_total']) * 100) : 0 }}],
            chart: {
                height: 350,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    hollow: { size: '70%', },
                    dataLabels: {
                        name: { show: false },
                        value: {
                            color: 'var(--dark)',
                            fontSize: '30px',
                            fontWeight: 800,
                            formatter: function(val) { return val + "%" }
                        }
                    }
                }
            },
            colors: ['#10b981'],
            stroke: { lineCap: 'round' }
        };
        new ApexCharts(document.querySelector("#radialTargetChart"), optionsRadial).render();
    });
</script>
