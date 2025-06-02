// Global chart state
window.forexChart = {
    widget: null,
    initialize: function() {
        const chartContainer = document.getElementById('chart');
        if (!chartContainer) {
            console.error('Chart container not found');
            return;
        }

        try {
            this.widget = new TradingView.widget({
                "container_id": "chart",
                "width": "100%",
                "height": "100%",
                "symbol": "BINANCE:BTCUSDT",
                "interval": "D",
                "theme": "dark",
                "style": "3",
                "toolbar_bg": "#f1f3f6",
                "hide_side_toolbar": true,
                "allow_symbol_change": true,
                "save_image": false,
                "hideideas": true,
            });
        } catch (error) {
            console.error('Failed to initialize TradingView widget:', error);
        }
    }
};

// Initialize chart when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure template is loaded
    setTimeout(() => {
        window.forexChart.initialize();
    }, 100);
});