
target_include_directories(magic-eightball PUBLIC ${CMAKE_INCLUDE_DIR})
target_compile_features(magic-eightball PUBLIC cxx_std_14)

add_executable(magic-eightball ${SOURCE_FILES})
