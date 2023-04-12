# eightball

If you're nostalgic like me, like to believe in predestination, or are just a huge nerd, I think you'll enjoy this tool.

## Compiling

At this time I have added both a Visual Studio 2017 and Xcode project for building Magic 8-Ball. You will need a C++11-compliant compiler to build it with anything crappier than those tools.

#### Xcode
In order to build Magic 8-Ball and make it avaialble in your account on macOS, you will need to edit the `Run` profile:

![Editing the run profile](http://wyatt.computer/pub/eightball-1.png)

then change the configuration to `Release`:

![Changing to release](http://wyatt.computer/pub/eightball-2.png)

finally, ⌘+B to build, and then you can highlight `eightball` underneath the `Products` node, which will show its full path on the right side of the IDE. Hit the -> icon and that'll open Finder. Then, copy it to `/usr/local/bin` and you're good to go.

#### Visual Studio
Just build in `Release` mode, then copy the resulting `eightball.exe` from the `Release` folder to anywhere in your `%PATH%`.

#### On the web
The 8-Ball is now available on the web: [http://gbtw.us/eightball/](http://gbtw.us/eightball/).
