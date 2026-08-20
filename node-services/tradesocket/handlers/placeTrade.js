import { placeTrade as relayPlaceTrade } from '../laravelClient.js';

/**
 * Node never touches the DB or wallet directly — it relays to Laravel's
 * internal API and passes the response straight back. If Laravel can't be
 * reached, this must not pretend a trade was placed: no debit happens
 * without going through Laravel, so a failed relay call means nothing was
 * placed, full stop.
 */
export function registerPlaceTradeHandler(socket, logger) {
  socket.on('trade:place', async (payload, ack) => {
    if (typeof ack !== 'function') return;

    let result;
    try {
      result = await relayPlaceTrade(socket.data.userId, payload || {});
    } catch (err) {
      logger.error('Trade relay call failed', { userId: socket.data.userId, error: err.message });
      return ack({ status: false, message: 'Trade service unavailable, please try again.' });
    }

    ack(result.body);
    if (result.body?.status) {
      socket.emit('trade:result', result.body);
    }
  });
}
