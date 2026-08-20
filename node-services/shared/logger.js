function line(level, service, msg, extra) {
  const ts = new Date().toISOString();
  const suffix = extra ? ' ' + JSON.stringify(extra) : '';
  console.log(`[${ts}] [${service}] [${level}] ${msg}${suffix}`);
}

export function createLogger(service) {
  return {
    info: (msg, extra) => line('info', service, msg, extra),
    warn: (msg, extra) => line('warn', service, msg, extra),
    error: (msg, extra) => line('error', service, msg, extra),
  };
}
