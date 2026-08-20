import jwt from 'jsonwebtoken';

/**
 * Verifies the key GET /realtime/token (Laravel's RealtimeAuthController)
 * issued to the browser. Purely local — no DB/Redis round trip — so this
 * never adds latency to opening a socket, let alone to a trade placed over
 * an already-open one.
 *
 * @returns {{ userId: number, sessionId: string } | null}
 */
export function verifyRealtimeToken(token) {
  try {
    const payload = jwt.verify(token, process.env.REALTIME_JWT_SECRET, { algorithms: ['HS256'] });
    if (!payload.sub || !payload.sid) return null;
    return { userId: Number(payload.sub), sessionId: String(payload.sid) };
  } catch {
    return null;
  }
}
