import { verifyRealtimeToken } from '../shared/auth.js';

/**
 * socket.io handshake middleware. Rejects the connection before it's ever
 * established if the token from GET /realtime/token doesn't verify — this
 * is the only auth check on the trade-placement path; once connected, an
 * `emit`/`ack` for a trade never re-checks it (see laravelClient.js's
 * heartbeat for the separate, out-of-band liveness check).
 */
export function socketAuthMiddleware(logger) {
  return (socket, next) => {
    const token = socket.handshake.auth?.token;
    const identity = token && verifyRealtimeToken(token);

    if (!identity) {
      logger.warn('Rejected socket handshake: invalid or missing token');
      return next(new Error('unauthorized'));
    }

    socket.data.userId = identity.userId;
    socket.data.sessionId = identity.sessionId;
    next();
  };
}
