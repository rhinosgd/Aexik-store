import AdminSidebar from "../_components/AdminSidebar";

export default function ContentLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return <main className="admin-console"><AdminSidebar active="content"/><section className="admin-route-content">{children}</section></main>;
}
