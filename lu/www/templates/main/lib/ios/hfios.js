
            $(document).ready(function() {
                $("#close").click(function() {
                	document.getElementById("img").style.display="none";
                	document.getElementById("close").style.display="none";
                });

                $("#a-img").click(function() {
                    document.getElementById("div").style.display="none";
                	document.getElementById("reddiv").style.display="block";
                });
            });
