import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";

window.pdfMake = pdfMake;
window.pdfFonts = pdfFonts;
pdfMake.vfs = pdfFonts.pdfMake.vfs;