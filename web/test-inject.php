<?php

# declare some variables, and then write some HTML nodes.
# include this in the middle of another file and see if the contents get 'injected'

$foo = "bar";

?>

<style>
.test-class {
    font-size: 3em;
    background: white;
    color:black;
}
</style>

<div class="test-class">
    <p>What's up, man? I'm from a different file.</p>
</div>
