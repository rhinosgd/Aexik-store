import AdminSidebar from "../_components/AdminSidebar";
import AdminSelectionInspector from "../_components/AdminSelectionInspector";

export default function CatalogLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return <main className="admin-console"><AdminSidebar active="catalog"/><section className="admin-route-content"><AdminSelectionInspector />{children}</section></main>;
}
