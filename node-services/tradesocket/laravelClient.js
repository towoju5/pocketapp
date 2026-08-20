const BASE_URL = process.env.LARAVEL_INTERNAL_BASE_URL || 'http://127.0.0.1:8000';
const SECRET = process.env.INTERNAL_API_SECRET;

async function post(path, body) {
  const res = await fetch(`${BASE_URL}${path}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      'X-Internal-Secret': SECRET,
    },
    body: JSON.stringify(body),
  });

  const json = await res.json().catch(() => ({}));
  return { ok: res.ok, status: res.status, body: json };
}

/** Relays a trade-placement payload to routes/internal.php's TradeRelayController::place(). */
export function placeTrade(userId, payload) {
  return post('/internal/trades', { user_id: userId, ...payload });
}

/** Backs the per-socket heartbeat — see RealtimeAuthController's docblock. */
export async function verifySession(userId, sessionId) {
  const { ok, body } = await post('/internal/session/verify', { user_id: userId, session_id: sessionId });
  return ok && body.valid === true;
}
