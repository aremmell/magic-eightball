################################################
# Magic Eightball make recipes
#
# Notes:
#	1. Intermediate files will be generated in build/obj.
# 	2. The Magic Eightball executable will be generated in
#	   build/bin.
#
#################################################

# directories
BLDDIR = build
SRCDIR   = src
INTDIR   = $(BLDDIR)/obj
BINDIR	 = $(BLDDIR)/bin

# if no installation directory is specified
# through the environment variable, set a default.
ifeq ($(INSTALLDIR),)
	INSTALLDIR = /usr/local/bin
	@echo setting install directory to /usr/local/bin
endif

# compiler settings
 CXX = clang++
 LDFLAGS  = -L/Library/Developer/CommandLineTools/SDKs/MacOSX.sdk/usr/lib
 CPPFLAGS = -I. -I/Library/Developer/CommandLineTools/SDKs/MacOSX.sdk/usr/include/c++/v1 \
			-I/Library/Developer/CommandLineTools/SDKs/MacOSX.sdk/usr/include
 CXXFLAGS = -Wpedantic -std=c++17 -DNDEBUG -fPIC -O3

# translation units
TUS = $(wildcard $(SRCDIR)/*.cpp)

# intermediate files
OBJ = $(patsubst %, $(INTDIR)/%, $(patsubst %.cpp, %.o, $(TUS)))

# executable
EXECUTABLE = $(BINDIR)/magic-eightball

# recipes
all: prep $(EXECUTABLE)

-include $(INTDIR)/*.d

$(BLDDIR) : prep
$(INTDIR) : $(BLDDIR)
$(BINDIR) : $(BLDDIR)

prep:
	$(shell mkdir -p $(BLDDIR) && \
			mkdir -p $(INTDIR) && \
			mkdir -p $(BINDIR))
	@echo directories prepared.

$(INTDIR)/%.o : $(SRCDIR/%.cpp) $(DEPS)
	$(CXX) --MMD -c -o $@ $< $(CXXFLAGS)

$(EXECUTABLE) : $(OBJ)
	$(CXX) -o $@ $(OBJ) $(CXXFLAGS) $(LDFLAGS)
	@echo built $(EXECUTABLE) successfully.

install: shared
	#@echo copying $(OUT_SHARED) to $(INSTALLDIR) and headers to $(INSTALLINC)...
	#$(shell cp -f $(OUT_SHARED) "$(INSTALLDIR)/" && \
	#        cp -f *.h *.hh "$(INSTALLINC)/")
	@echo install not yet written.

.PHONY: clean

clean:
	$(shell rm -f $(BLDDIR)/* >/dev/null 2>&1 && \
			rm -f $(INTDIR)/* >/dev/null 2>&1 && \
	        rm -f $(BINDIR)/* >/dev/null 2>&1)
	@echo cleaned successfully.
