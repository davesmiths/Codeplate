
Codeplate

[#list]
    [#template]
        <li>[content]</li>
    [/template]
[/list]

[#definition]
    <li>[dt]</li>
[/definition]


[#template]
    <!DOCTYPE html>
    <html>
    <title>[content]</title>
    <body>
        <h1>[content]</h1>
    </body>
    </html>
[/template]





[#listofanimals]
    <li>[animal]</li>
[/listofanimals]

[#listofanimals]
    [#orderby]size[/orderby]
    [#template]
        <li>[animal]</li>
    [/template]
[/listofanimals]
