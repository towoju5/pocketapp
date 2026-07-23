import TradingDashboard from './trading/TradingDashboard.js';
import { updateOrInsertTradeCard, startCountdowns, rearmCountdowns } from './trading/tradeCards.js';

window.updateOrInsertTradeCard = updateOrInsertTradeCard;
window.startCountdowns = startCountdowns;
window.rearmCountdowns = rearmCountdowns;

document.addEventListener('DOMContentLoaded', () => {
    const configEl = document.getElementById('trading-dashboard-config');
    if (!configEl) return;

    const options = JSON.parse(configEl.textContent);
    window.tradingDashboard = new TradingDashboard(options);
});
