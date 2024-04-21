<style>
    .img-detail{
       width: 141px;
       height: 176px;
       object-fit: cover;
       border-radius: 5px;
   }
   
   table td, tfoot th, .table-title th{
       border: 1px solid black;
       border-collapse: collapse;
       padding: 3px;
   }

   @media screen {

       body {
           font-family: 'Courier New', Courier, monospace;
           
       }
       table td{
                 border: 0px;
                line-height: 0.8;
                padding-left: 0 !important;
           }

       .logo-atas{
           width: 40px;
           height: 40px;
           position: absolute;
           left: 250px;
           top: -15px;
       }
       table.print-friendly tr td, table.print-friendly tr th {
               page-break-inside: avoid;
           }

   }

   /* print styles */
   @media print {

       body {
           margin: 0;
           color: #000;
           background-color: #fff;
       }
           table td{
                 border: 0px;
                line-height: 0.5;
                padding-left: 0 !important;
           }
           
           .logo-atas{
               width: 40px;
               height: 40px;
               position: absolute;
               left: 200px;
               top: 15px;
           }
           table.print-friendly tr td, table.print-friendly tr th {
               page-break-inside: avoid;
           }

       }

       .top-title{
            font-size: 16px;
            font-weight: bold;
            line-height: normal;
       }
       .middle-title{
            font-weight: bold;
            font-size: 18px;
            text-align: center;
       }
       
    

</style>