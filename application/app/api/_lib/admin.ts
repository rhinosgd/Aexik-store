import { env } from "cloudflare:workers";

export function isAdmin(request: Request) {
  const bindings = env as unknown as { APEXIK_ADMIN_KEY?: string };
  const configured = bindings.APEXIK_ADMIN_KEY ?? process.env.APEXIK_ADMIN_KEY;
  return Boolean(configured && request.headers.get("x-apexik-admin-key") === configured);
}

export function imageRule(kind: string, width: number, height: number) {
  const expected = kind === "product" ? [1600, 1600] : kind === "moment" ? [1600, 900] : kind === "banner" ? [2200, 800] : null;
  return expected && width === expected[0] && height === expected[1];
}
