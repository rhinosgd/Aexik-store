"use client";

import { useEffect, useState } from "react";

type SelectionStyle = { fontFamily: string; fontSize: string; color: string; fontWeight: string; bold: boolean; italic: boolean; underline: string };
const emptyStyle: SelectionStyle = { fontFamily: "—", fontSize: "—", color: "—", fontWeight: "—", bold: false, italic: false, underline: "无" };

function readStyle(element: Element): SelectionStyle {
  const computed = window.getComputedStyle(element);
  const weight = computed.fontWeight;
  return { fontFamily: computed.fontFamily.replaceAll('"', "").split(",")[0].trim(), fontSize: computed.fontSize, color: computed.color, fontWeight: weight, bold: weight === "700" || Number(weight) >= 600, italic: computed.fontStyle === "italic", underline: computed.textDecorationLine.includes("underline") ? "下划线" : "无" };
}

export default function AdminSelectionInspector() {
  const [style, setStyle] = useState<SelectionStyle>(emptyStyle);
  const [target, setTarget] = useState("未选择文字");
  useEffect(() => {
    const inspect = () => {
      const selection = window.getSelection();
      const active = document.activeElement;
      const anchor = selection?.anchorNode;
      const selectedElement = anchor ? anchor.nodeType === Node.ELEMENT_NODE ? anchor as Element : anchor.parentElement : null;
      const element = selectedElement || (active instanceof HTMLElement ? active : null);
      if (!element || !element.closest(".admin-route-content")) return;
      setStyle(readStyle(element));
      const selectedText = selection?.toString().trim();
      setTarget(selectedText ? `已选 ${selectedText.length} 个字符` : `当前字段：${(element as HTMLInputElement).name || element.tagName.toLowerCase()}`);
    };
    document.addEventListener("selectionchange", inspect);
    document.addEventListener("mouseup", inspect);
    document.addEventListener("keyup", inspect);
    document.addEventListener("focusin", inspect);
    return () => { document.removeEventListener("selectionchange", inspect); document.removeEventListener("mouseup", inspect); document.removeEventListener("keyup", inspect); document.removeEventListener("focusin", inspect); };
  }, []);
  return <div className="global-style-info-bar" aria-live="polite"><strong>当前编辑区域样式</strong><span>{target}</span><em>字体 {style.fontFamily}</em><em>字号 {style.fontSize}</em><em>颜色 <i style={{ background: style.color === "—" ? "transparent" : style.color }} /> {style.color}</em><em>字重 {style.fontWeight}</em><em>粗体 {style.bold ? "是" : "否"}</em><em>斜体 {style.italic ? "是" : "否"}</em><em>下划线 {style.underline}</em></div>;
}
