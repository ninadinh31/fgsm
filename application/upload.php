<h3 align="center">Upload Documents</h3>
            <table align="center" width="488" height="53" border="1">
              <tbody>
                <tr>
                  <td width="273"><strong>Document Type</strong></td>
                  <td width="199"><strong>Document</strong></td>
                  <td width="199">&nbsp;</td>
                </tr>
                <?php do { ?>
                <tr>
                  <td><?php echo $row_rsDocuments['docCategory']; ?></td>
                  <td><a href="uploads/<?php echo $row_rsDocuments['docName'] ?>"><?php echo $row_rsDocuments['docName'];?></a></td>
                  <td><input name="txtdocID" type="hidden" id="txtdocID" value="<?php echo $row_rsDocuments['docID']; ?>">
                    <a href="DeleteDoc1.php?docID=<?php echo $row_rsDocuments['docID'] ?>">Delete</a></td>
                </tr>
                <?php } while ($row_rsDocuments = mysql_fetch_assoc($rsDocuments)); ?>
              </tbody>
            </table>
            <p align="center">
              <input name="butUploadDocs" type="button" id="butUploadDocs" onClick="MM_goToURL('parent','Documents1.php');return document.MM_returnValue" value="Upload Documents">
            </p>