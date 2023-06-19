# magic-eightball

If you're nostalgic like me, believe in fate, or are just a nerd (*read: not dork; big difference*), I think you'll enjoy the *magic eightball*.

I find it particularly useful & funny to ask if you should or shouldn't do some thing that you can't make your mind up about.

When you're in that situation along with a friend or family member, it's even more enjoyable. One of you can ask the magic eightball, and be pleased or disappointed–depending on the answer.

## Tribute

Since I cannot actually manufacture a plastic ball filled with liquid, I have at least ensured that the set of answers that are possible for any given question are the original set from the toy.

## Installation

I am impressed with [CMake Tools](https://marketplace.visualstudio.com/items?itemName=ms-vscode.cmake-tools). I've always wanted to try `CMake` because it seems like if you do it right, you can build your software anywhere–and that appears to be true in this case. There is a learning curve for sure, but I've become a fan.

You either need to install the CMake Tools extension and then execute `CMake: Configure`, and then `CMake: Build`. I have only tested this on macOS and Ubuntu, so *YMMV*.

If the build is successful, you will find the `build/magic-eightball` binary ready for use.

> I have not configured an install step; it's on the TODO list, so you'll have to manually move it somewhere useful.

## Basic usage

The CLI app is very straightforward–if you execute it with the `-q` parameter, it will just provide and answer and quit. Otherwise it enters an interactive mode where you can keep asking questions until you enter `q`.

I have some plans for the CLI app that I am excited about:

- I would like to add new categories of responses. I know this is a breakaway from the traditional toy, but I think if we crowdsourced it, we could come up with some gems. Perhaps different categories of new responses.
- I am going to rewrite the way the ASCII art is generated; it will be off by default, and there will be several options to choose from when opting for that type of output.

## Web interface

There is now a complete working PHP-based web interface to the `magic-eightball` program. You'll find that it's in the `web` folder, and really all you'd need to do to start your own instance is upload the contents of the `web` folder *(to a server with PHP obviously)*, install `magic-eightball` on the server where PHP could interact with it and you'd be set.

I have just tonight completed a somewhat-presentable version of the web interface, which is online at [https://rml.dev/magic-eightball/](https://rml.dev/magic-eightball/). I've got work to do on reactive/mobile stuff, but the main bits should at least be functioning.

I would grealy appreciate any input you might have about this project, so please write if you have something in mind!

## Technical jargon

For those of you wondering how it generates pseudo-random answers, it utilizes the STL's [Mersenne Twister](https://en.wikipedia.org/wiki/Mersenne_Twister) implementation `mt19937`, and seeded with the [CRC32](https://en.wikipedia.org/wiki/Cyclic_redundancy_check) checksum of the question input text.

### TODO

- [ ] URL is hard-coded in Open Graph tags for images and the current URL.
