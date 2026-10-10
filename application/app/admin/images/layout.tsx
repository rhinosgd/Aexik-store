import AdminSidebar from "../_components/AdminSidebar";
import AdminSelectionInspector from "../_components/AdminSelectionInspector";

export default function ImagesLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return <main className="admin-console"><AdminSidebar active="images"/><section className="admin-route-content"><AdminSelectionInspector />{children}</section></main>;
}
