<h3>Upload Documents</h3>
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td><strong>Document Type</strong></td>
                  <td><strong>Document</strong></td>
                  <td>&nbsp;</td>
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
              <input name="butUploadDocs" type="button" id="butUploadDocs" class="btn btn-primary" onClick="MM_goToURL('parent','Documents1.php');return document.MM_returnValue" value="Upload Documents">
            </p>