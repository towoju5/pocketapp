# node-services

Standalone Node real-time layer for pocketapp — see `/home/ignite/.claude/plans/tender-tumbling-hummingbird.md`
for the full migration plan this implements. Two independent daemons, own
`package.json`/lockfile, deliberately separate from the root Vite build.

## Status (as of this implementation)

Built and smoke-tested (live Brokeret feed, real Laravel HTTP round trips,
real wallet debits against a demo account), but **not yet wired into
Supervisor and not part of normal local dev** — nothing here runs unless you
start it manually. `TRADESOCKET_INTERNAL_URL` in Laravel's `.env` is left
unset, so `TradeSettlementService`'s settlement push safely no-ops until you
deploy `tradesocket`. Still to do before either service should run
continuously: Supervisor `.conf` files (mirror `deploy/supervisor/pocketapp-brokeret-ui-stream.conf`),
frontend wiring (`socket.io-client`, `brokeretWsFeed.js`, `tradeSocket.js`,
feature flags), and a staged rollout per the plan's Phase 1/2 sequencing.

## pricefeed/

Node port of `StreamBrokeretFeed`/`BrokeretFeedService` — connects to
Brokeret's WS feed, writes into the **same** Redis key namespace the PHP
daemon uses (`brokeret:latest:*`, `brokeret:ticks:*`), and broadcasts to
browsers over its own plain WebSocket server (`ws/brokeret-feed`) instead of
Ably/Reverb.

**Only one of `ticks:stream-brokeret-ui` (PHP) and this service should be
running at a time** — both are alternative writers to the same Redis keys.
Running both simultaneously won't corrupt anything (writes are idempotent,
same shape) but doubles the upstream Brokeret connection and broadcast
traffic. Cutover/rollback is just: stop one, start the other.

```
npm run pricefeed
```

## tradesocket/

socket.io server for trade placement (`trade:place`) and settlement push
(`trade:updated`). Never touches the DB or wallet directly — relays every
placement to Laravel's `POST /internal/trades` (guarded by
`EnsureInternalServiceRequest` / `INTERNAL_API_SECRET`) and receives
settlement results via its own `POST /internal/push/trade-updated` endpoint,
which `TradeSettlementService::pushToTradeSocket()` calls best-effort.

Auth: browsers fetch a signed key once from Laravel's `GET /realtime/token`
and pass it in the socket.io handshake (`auth.token`); verified locally
(no DB round trip) by `shared/auth.js`. Liveness/revocation is handled by a
background heartbeat (`SESSION_HEARTBEAT_INTERVAL_MS`, default 5 min) that
re-checks the embedded Laravel session id via `POST /internal/session/verify`
and disconnects the socket if it's no longer valid — see
`RealtimeAuthController`'s docblock for why revocation isn't handled by a
short token expiry instead.

```
npm run tradesocket
```

## Setup

```
cp .env.example .env   # fill in REALTIME_JWT_SECRET / INTERNAL_API_SECRET
                        # to match Laravel's own .env — same values, both sides
npm install
```
