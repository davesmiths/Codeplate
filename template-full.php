<!DOCTYPE html>
<html>
<title>Codeplate</title>
<hr />
---
        [#anum]policecar[/anum]
<br />XXXXXXXXXXXXXXXXXX<br />
[#num]

    555
    [#other]Thing 1[/other]
    [#other]Thing 2[/other]

    [#template]
        <hr />
        <h2>Heading for Num</h2>
        <p>The number captured: <strong>[num]</strong></p>
        [police]
        <br />fire start<br />
        [#fire]
            [#content]456[/content]
        [/fire]
        <hr />
    [/template]



[/num]
<br />XXXXXXXXXXXXXXXXXX<br />
[#poopExists]
poopExists content 1<br />
[/poopExists]

[poop]<br />

[#poopExists]
[poop]
poopExists content 2<br />
[/poopExists]

---
<h1>[#title]Dave Smith[/title] - [test]</h1>
<em>[atitle] - [test]</em>

[#quote]

    [#way]
        "Way to go!"
    [/way]
    [#boop]
        "BOOOP!"
    [/boop]

    [#template]
        <blockquote>
            [way]
            [#boop][/boop]
            [boop]
        </blockquote>
    [/template]

[/quote]

[#thing]
    "Thing to go!"
    [#template]
        <p><strong>
            [thing]
        </strong></p>
    [/template]
[/thing]

[#profiles]
    [#p]Default[/p]
    [#profile]
        [#name]
            Dave
        [/name]
        [#image]
            <img src="" alt="Image A" />
        [/image]
        [#biog]
            Blah
        [/biog]
        [#p]
            Yes
        [/p]
    [/profile]

    [#profile]
        [#name]
            Diana
        [/name]
        [#image]
            <img src="" alt="Image B" />
        [/image]
        [#biog]
            Blah blah
        [/biog]
    [/profile]

    [#profiles]
    Profile
        [#profile]
            <section class="profile">
                <h2>[image][name]</h2>
                <div>[p] [biog]</div>
            </section>
        [/profile]
    [/profiles]

[/profiles]


[#people]
people
    <dl>
    <dt>Title: [title]</dt>
    <dd>
    <p>Name: [name]</p>
    <p>output: [output]</p>

    [#publications]
        [#pre]<p>pre</p><ul>[/pre]
        <li>Year: [year],
            title: [title], name: [name]</li>
        [#post]</ul><p>post</p>[/post]
    [/publications]


</dd>
</dl>

[/people]
<?php /**/ ?>



    [#list]
        [#dave]green[/dave]

        [#item]
            [#dave]Red[/dave]
            [#url]a.html[/url]
            [#text]A[/text]
        [/item]
        [#item]
            [#url]b.html[/url]
            [#text]B[/text]
        [/item]
        [#item]
            [#url]c.html[/url]
            [#text]C[/text]
        [/item]
        [#item]
            [#url]c.html[/url]
            [#text]C[/text]
        [/item]

        <ul style="background:#ddd;padding:20px;">
        [#list]
            [#item]<li style="background:[dave]">ytfytfytf<a href="[url]">[text] [test]</a></li>
            [/item]
        [/list]
        </ul>
    [/list]


<?php /**/ ?>


</html>
