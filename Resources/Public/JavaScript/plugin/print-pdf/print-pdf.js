/**
 * phantomjs script for printing presentations to PDF.
 *
 * Example:
 * phantomjs print-pdf.js "http://lab.hakim.se/reveal-js?print-pdf" reveal-demo.pdf
 *
 * @author Manuel Bieh (https://github.com/manuelbieh)
 * @author Hakim El Hattab (https://github.com/hakimel)
 * @author Manuel Riezebosch (https://github.com/riezebosch)
 */
var system=require("system"),probePage=new WebPage,printPage=new WebPage,inputFile=system.args[1]||"index.html?print-pdf",outputFile=system.args[2]||"slides.pdf";null===outputFile.match(/\.pdf$/gi)&&(outputFile+=".pdf"),console.log("Export PDF: Reading reveal.js config [1/4]"),probePage.open(inputFile,function(e){console.log("Export PDF: Preparing print layout [2/4]");var t=probePage.evaluate(function(){return Reveal.getConfig()});t?(printPage.paperSize={width:Math.floor(t.width*(1+t.margin)),height:Math.floor(t.height*(1+t.margin)),border:0},printPage.open(inputFile,function(e){console.log("Export PDF: Preparing pdf [3/4]"),printPage.evaluate(function(){Reveal.isReady()?window.callPhantom():Reveal.addEventListener("pdf-ready",window.callPhantom)})}),printPage.onCallback=function(e){setTimeout(function(){console.log("Export PDF: Writing file [4/4]"),printPage.render(outputFile),console.log("Export PDF: Finished successfully!"),phantom.exit()},0)}):(console.log("Export PDF: Unable to read reveal.js config. Make sure the input address points to a reveal.js page."),phantom.exit(1))});