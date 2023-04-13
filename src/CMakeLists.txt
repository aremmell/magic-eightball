target_include_directories(${PROJECT_NAME} PUBLIC ${CMAKE_INCLUDE_DIR})
target_compile_features(${PROJECT_NAME} PUBLIC cxx_std_14)

add_executable(${PROJECT_NAME} ${SOURCE_FILES})
