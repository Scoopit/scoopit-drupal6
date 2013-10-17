<?php 
// Parameters
// ----------
// $pageCount : the total of pages
// $page : the current page
//
?>
 
<?php 
	if($pageCount > 1) { 
		//compute url with page -> little bit hacky
		$queryString = $_SERVER['QUERY_STRING'];
		if (strlen($queryString) == 0) {
			$queryString = "1=1";
		} else {
			//remove page parameter if it is
			$queryString = preg_replace('/(.*)(?|&)' . "page" . '=[^&]+?(&)(.*)/i', '$1$2$4', $queryString . '&');
			$queryString = substr($queryString, 0, -1);
		}
	
?>
            <center>
              <table class="paginator" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
                <tr>
                  <td class="previousNext">
                    <?php
                      if($page == 1)
                        echo "Previous";
                      else
                        echo "<a href='?".$queryString."&page=".($page-1)."'>Previous</a>";
                    ?>
                  </td>
                  <?php if($page > 4) { ?>
                    <td class="page"><a href="?page=1">1</a></td>
				            <td class="petitsPoints">...</td>
		          <?php } if($page > 3) { ?>
                    <td class="page"><a href="?<?php echo $queryString; ?>&page=<?php echo $page-3 ?>"><?php echo $page-3 ?></a></td>
                  <?php } if($page > 2) { ?>
                    <td class="page"><a href="?<?php echo $queryString; ?>&page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a></td>
                  <?php } if($page > 1) { ?>
                    <td class="page"><a href="?<?php echo $queryString; ?>&page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a></td>
                  <?php } ?>
                  <td class="page"><span class="selected"><?php echo $page ?></span></td>
                  <?php if($page+1 <= $pageCount) { ?>
                    <td class="page"><a href="?<?php echo $queryString; ?>&page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></td>
                  <?php } if($page+2 <= $pageCount) { ?>
                    <td class="page"><a href="?<?php echo $queryString; ?>&page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></td>
                  <?php } if($page+3 <= $pageCount) { ?>
                    <td class="page"><a href="?<?php echo $queryString; ?>&page=<?php echo $page+3 ?>"><?php echo $page+3 ?></a></td>
                  <?php } if($page+4 <= $pageCount) { ?>
                    <td class="petitsPoints">...</td>
				            <td class="page"><a href="?<?php echo $queryString; ?>&page=<?php echo $pageCount ?>"><?php echo $pageCount ?></a></td>
                  <?php } ?>
                  <td class="previousNext">
                    <?php
                      if($page > $pageCount - 1)
                        echo "Next";
                      else
                        echo "<a href='?".$queryString."&page=".($page+1)."'>Next</a>";
                    ?>
                  </td>
                </tr>
              </table>
            </center>
 <?php } ?>